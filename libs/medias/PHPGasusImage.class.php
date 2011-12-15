<?php


##########
# Usage: #
##########

/*
 
// Instanciation
$img = new PHPGasusImage($src); 						// Directly loading source pics
$img = new PHPGasusImage(); $img = $img->open($src); 	// Deferred
$img = PHPGasusImage::getInstance($src); 				// Singleton, direct loading
$img = PHPGasusImage::getInstance()->open($src); 		// Singleton, deferred loading

// Metadata
$mime = $img->mime(); 									// Returns image mimetype
$type = $img->type(); 									// Returns image type (jpg,png,gif,...)
$info = $img->info(); 									// Returns image info: width, height, type, mimetype

// Clone (returns the cloned image)
$img->clone(); 											// No args, copy will be named {$src filename}_{timestamp}.{$src ext}
$img->clone('path/to/created/file.ext'); 				// Copy filepath passed with explicit extension, copy will of the passed extension type
$img->clone('path/to/created/file'); 					// Copy filepath passed without extension, copy will have the same type than source
$img->clone(array('prefix' => 'tn_')); 					// Created filename will be prefixed by passed value
$img->clone(array('suffix' => '_mobile')); 				// Created filename will be suffixed by passed value 

// Duplicating = clone (except it returns the original)
$img->duplicate();

// Resizing
$img->resize($w); 										// Use passed width, keep source ratio
$img->resize($w, null); 								// Use passed width, keep source ratio
$img->resize(null, $h); 								// Use passed height, keep source ratio
$img->resize($w, $h); 									// Use passed width & height, source ratio is lost
$img->resize(array('width' => $w)); 					// Use passed width, keep source ratio
$img->resize(array('height' => $h)); 					// Use passed height, keep source ratio
$img->resize(array('width' => $w, 'height')); 			// Use passed width & height, source ratio is lost
$img->resize(array('width' => $w, 'ratio' => $ratio)); 	// Use passed width & ratio, compute height accordingly
$img->resize(array('height' => $h, 'ratio' => $ratio)); // Use passed width & ratio, compute height accordingly

// Saving/outputin
$img->save(); 											// Write pics file
$img->toDataURI(); 										// Returns dataURI of the image
$img->output(); 										// Output direclty the image into the browser

// Misc
$img->compress($quality); 								//
 
$img->thumbs($w, $h); 									// = $img->clone(array('width' => $w, 'height' => $h))
$img->crop(); 											//
$img->rotate(); 										//
$img->watermark(array('logo' => 'path/to/logo')); 		//
$img->convert(array('to' => 'png', 'quality' => 80)); 	//

*/

class PHPGasusImage
{
	public $src;
	public $image;
	
	private static $_instance;
	
	public function __construct($src = null)
	{
//var_dump(__METHOD__);
		$args 		= func_get_args();
		$this->lib 	= extension_loaded('imagick') ? 'imagick' : ( extension_loaded('gd') ? 'gd' : null );
		$extExt 	= strpos('Windows', php_uname()) !== false ? 'dll' : 'so'; 
		
		
		if ( extension_loaded('imagick') )
		{
			$this->lib = 'imagick';
		}
		elseif ( extension_loaded('gd') )
		{
			$this->lib = 'imagick';
		}
		// Otherwise, try to dynamically load extensions
		elseif ( dl('imagick.' . $extExt ) )
		{
			$this->lib = 'imagick';
		}
		elseif ( dl('gd.' . $extExt ) )
		{
			$this->lib = 'imagick';
		}
		else
		{
			Throw new Exception('Missing image handling extension. Please install ImageMagic or GD extension.' . PHP_EOL);
		}
		
		if ( $src ){ $this->src = $src; }
		
		return isset($this->src) ? $this->open($this->src) : $this;
	}
	
	public static function getInstance($src = null)
	{
//var_dump(__METHOD__);
		if ( !(self::$_instance instanceof self) ){ self::$_instance = new self($src); }
		
		return self::$_instance;
	}
	
	public function __call($name, $args)
	{
		if ( $name === 'clone' ){ return call_user_func_array(array($this, '_clone'), $args); }
	}
	
	public function open($src)
	{
//var_dump(__METHOD__);
		$this->src = $src;
				
		// Do not continue if the source file does not exists 
		if ( !file_exists($src) ){ return $this; }
		
		$this->src = $src;
		
		
		$this->image = $this;
		
		if ( $this->lib === 'imagick' )
		{
			try
			{
				$this->image = new Imagick($src);
			}
			catch (Exception $e)
			{
				echo 'Error loading image ' . $this->src . PHP_EOL . $e->getMessage() . PHP_EOL;
			}

			try
			{
				$this->image->readImage($src);
			}
			catch (Exception $e)
			{
				echo 'Error reading image ' . $this->src . PHP_EOL . $e->getMessage() . PHP_EOL;
				$this->image = false;
			}
		}
		elseif ( $this->lib === 'gd' )
		{
			switch( $this->mime() ) 
			{
				case 'image/jpg':
					$this->image = imagecreatefromjpeg($src);
					break;
				case 'image/gif':
					$this->image = imagecreatefromgif($src);
					break;
				case 'image/png':
					$this->image = imagecreatefrompng($src);
					break;
				default:
					$this->image = false;
					break;
			}	
		}
		else
		{
			$this->image = false;
		}
		
		return $this;
	}
	
	// Get image info
	public function info()
	{
//var_dump(__METHOD__);
		
		if ( !$this->image ){ return $this->false; }
		
		if ( isset($this->info) ){ return $this->info; }
		
		switch($this->lib)
		{
			case 'gd':
					// TODO
					break;
			case 'imagick':
					//$dim = $this->image->getSize();
					$dim = $this->image->getImageGeometry();
					$this->info = array(
						'width' 	=> $dim['width'],
						'height' 	=> $dim['height'],
						'mime' 		=> $this->mime(),
						'type' 		=> $this->type(),
					);
					break;
			default:
					break;
		}
		
//var_dump($this->info);
		
		return $this->info();
	}
	
	
	public function mime()
	{
//var_dump(__METHOD__);
		if ( !$this->image ){ return false; }
		
		if ( isset($this->mime) ){ return $this->mime; }
		
		if ( extension_loaded('fileinfo') )
		{
			$finfo 		= finfo_open(FILEINFO_MIME_TYPE);
			$this->mime = finfo_file($finfo, $this->src);
		}
		elseif ( extension_loaded('exif') )
		{
			$this->mime = exif_imagetype($src);  // 1 = IMAGETYPE_GIF, 2 = IMAGETYPE_JPEG, 3 = IMAGETYPE_PNG
		}
		elseif ( extension_loaded('gd') )
		{
			$info 		= getimagesize($filename);
			$this->mime = image_type_to_mime_type($info[2]); 
		}
		
//var_dump($this->mime);
		
		return $this->mime;
	}
	
	public function type()
	{
//var_dump(__METHOD__);
		
		if ( !$this->image ){ return false; }
		
		if ( isset($this->type) ){ return $this->type; }
		
		switch($this->lib)
		{
			case 'gd':
				$info 		= getimagesize($filename);
				$this->type = str_replace('.', '', image_type_to_extension($info[2]));
					break;
			case 'imagick':
				//$info 		= $this->image->getImageType();
				$this->type = str_replace(array('image/', 'jpeg'), array('', 'jpg'), $this->mime());
					break;
			default:
					break;
		}
		
//var_dump($this->type);
		
		return $this->type;
	}
	
	
	public function _clone()
	{
		$args = func_get_args($args);
		
		call_user_method_array(array($this, 'duplicate'), $args);
		
		return $this->open($this->dest);
	}
	
	public function duplicate()
	{
//var_dump(__METHOD__);
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }

		$args 		= func_get_args();
		$srcInfo 	= pathinfo($this->src);
		
		// Get params
		$p 			= !empty($args[0]) && is_array($args[0]) ? $args[0] : array();
		
		// Extends default params by passed ones
		$p = array_merge(array(
			'extension' => '.' . $srcInfo['extension'],
			'type' 		=> $srcInfo['extension'],
			'prefix' 	=> '',
			//'suffix' 	=> $_SERVER['REQUEST_TIME'],
			'suffix' 	=> '',
			'quality' 	=> 100,
			'format' 	=> null,
		), $p);
		
		// 
		$tmpDest 	= !empty($args[0]) && is_string($args[0]) ? $args[0] : $this->src;
		$destInfo 	= pathinfo($tmpDest);
		
		//: $srcInfo['dirname'] . '/' . $srcInfo['filename'] . time() . $p['extension'];
		
		$this->dest = $destInfo['dirname'] . '/' . $p['prefix'] . $destInfo['filename'] . $p['suffix'] . $p['extension'];

//var_dump($this->image);						
//var_dump($this->dest);
		
		if ( $this->lib === 'gd' )
		{
			switch( $this->mime)
			{
				case 'jpg':
					if (imagetypes() & IMG_JPG) {
						imagejpeg($this->image, $this->dest, $p['quality']);
					}
					break;
	
				case 'gif':
					if (imagetypes() & IMG_GIF) {
						imagegif($this->image, $this->dest);
					}
					break;
	
				case 'png':
					// Quality goes from 0 to 9 (best)
					$quality = 9 - round(($p['quality']/100) * 9);
	
					if (imagetypes() & IMG_PNG) {
						//if ($this->params['pngBit'] == 8) {imagetruecolortopalette($this->image, false, 255);}
						imagepng($this->image, $savePath, $quality);
					}
					break;
	
				// ... etc
	
				default:
					// *** No extension - No save.
					break;
			}
	
			//imagedestroy($this->image);
		}
		elseif ( $this->lib === 'imagick' )
		{			
			$format = strtolower( !empty($p['format']) ? $p['format'] : $p['type'] );
//var_dump('format: ' . $format);
//var_dump('depth: ' . $this->image->getImageDepth());
//var_dump('get image type: ' . $this->image->getImageType());
//var_dump('get image format: ' . $this->image->getImageFormat());
			//if ( $format === 'png8' ){ $this->image->setImageDepth(4); }
			if ( $format === 'png8' ){ $this->image->setImageColorSpace(256); }
			$this->image->setFormat($format);
			
			try
			{
				$this->image->setImageFormat($format);
			}
			catch (Exception $e)
			{
				echo 'Error setting image format ' . $this->src . PHP_EOL . $e->getMessage() . PHP_EOL;
			}
			
//var_dump('new depth: ' . $this->image->getImageDepth());
//var_dump('new format: ' . $this->image->getImageFormat());
			
			if ( $p['type'] === 'jpg' )
			{
				$this->image->setImageCompression(imagick::COMPRESSION_JPEG);
				$this->image->setImageCompressionQuality($p['quality']);
			}
			
			//$this->image->writeImage($this->dest);
		}

		return $this;
	}
	
	public function resize()
	{
//var_dump(__METHOD__);
		
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }
		
		$args 		= func_get_args(); 								// Get passed args
		$src 		= $this->info(); 								// Get source info
		
		// Get & test passed dimensions or default them to 'auto'
		$isArray 	= !empty($args[0]) && is_array($args[0]);
		$p 			= array(
			'width' => $isArray 
				? ( !empty($args[0]['width']) && preg_match('/([\d]+%?|auto)/', $args[0]['width']) ? $args[0]['width'] : 'auto' )
				: ( !empty($args[0]) && preg_match('/([\d]+%?|auto)/', $args[0]) ? $args[0] : 'auto' ),
			'height' => $isArray
				? ( !empty($args[0]['height']) &&  preg_match('/([\d]+%?|auto)/', $args[0]['height']) ? $args[0]['height'] : 'auto' )
				: ( !empty($args[1]) && preg_match('/([\d]+%?|auto)/', $args[1]) ? $args[1] : 'auto' ),
			'ratio' => $isArray && !empty($args[0]['ratio']) && is_float($args[0]['ratio']) 
				? abs($args[0]['ratio']) 
				: $src['width'] / $src['height'],
			//'quality' => $isArray && !empty($args[0]['quality']) && $args[0]['quality'] > 0 && $args[0]['quality'] < 100
				//? $args[0]['quality'] 
				//: 100,
		);
//var_dump($p);
		
		// Do not continue if both of the interpreted dimensions are 'auto'
		if ( $p['width'] === 'auto' && $p['height'] === 'auto' ){ throw new Exception("Invalid dimensions. Please pass at least 1 valid dimension"); }
		
		// Do not continue if resize dimensions are identical to source dimensions
		if ( $p['width'] === $src['width'] && $p['height'] === $src['height'] ){ throw new Exception("Resize dimensions are identical to source dimensions"); }
		
		// If only one of the 2 dimensions is passed, calculate the other
		$p['width'] = round($p['width'] === 'auto' ? $p['ratio'] * $p['height'] : $p['width']);
		$p['height'] = round($p['height'] === 'auto' ? $p['width'] / $p['ratio'] : $p['height']);
	
//var_dump($p);
		
		if ( $this->lib === 'gd' )
		{
		}
		elseif ( $this->lib === 'imagick' )
		{	
			try
			{
				$this->image->resizeImage($p['width'], $p['height'], imagick::FILTER_UNDEFINED, 1);
			}
			catch (Exception $e)
			{
				echo 'Error resizing image ' . $this->src . PHP_EOL . $e->getMessage() . PHP_EOL;
			}
		}
		
		return $this;
	}
	
	public function convert()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }
		
		// TODO
		
		return $this;
	}
	
	public function compress($quality = 100, $params = array())
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }
		
		return $this;
	}
	
	public function crop()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }
		
		// TODO
		
		return $this;
	}
	
	public function rotate()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }
		
		// TODO
		
		return $this;
	}
	
	public function watermark()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return $this; }
		
		// TODO
		
		return $this;
	}
	
	public function save()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return false; }
		
		if ( $this->lib === 'gd' )
		{
			// TODO
		}
		elseif ( $this->lib === 'imagick' )
		{
			try
			{
				$this->image->writeImage($this->dest);
			}
			catch (Exception $e)
			{
				echo 'Error saving image ' . $this->src . PHP_EOL . $e->getMessage() . PHP_EOL;
			}
			
		}
		
		return $this;
	}
	
	public function output()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return false; }
		
		// TODO
		return $this;
	}
	
	public function toDataURI()
	{
		// Do not continue if the image is not opened 
		if ( !$this->image ){ return false; }
		
		// TODO
		
		return $this;
	}
	
	public function close()
	{
		if ( $this->lib === 'gd' )
		{
			imagedestroy($this->image);
		}
		elseif ( $this->lib === 'imagick' )
		{
			$this->image->clear();
			$this->image->destroy();
		}
	}
}	

?>