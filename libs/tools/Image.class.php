<?php

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

Class Image extends Application
{
	// *** Class variables
	private $image;
	private $mime;
    private $width;
    private $height;
	private $imageResized;
	private static $_instance;
	private $params;
	
	public $data;

	function __construct()
	{
	}

	## --------------------------------------------------------

	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}

	## --------------------------------------------------------
	
	public function duplicate($params)
	{
		// Shortcut for params
		$p = $params;
		
		// Check args, file existence and file type
		if ( empty($p['src']) || !file_exists($p['src']) || !$this->getMime($p['src']) ) { return false; }
		
		// Open image to process
		$this->image 	= $this->openImage($p['src']);

	    // *** Get width and height
	    $this->width  	= imagesx($this->image);
	    $this->height 	= imagesy($this->image);
	    
		// Get params (extending the empty one with default values)
		$srcInfo 		= pathinfo($p['src']);
		$this->params 	= array(
			'src' 			=> $p['src'],
			'srcPath' 		=> !empty($srcInfo['dirname']) ? $srcInfo['dirname'] . '/' : '',
			'srcFile' 		=> $srcInfo['basename'],
			'outputFormat' 	=> 'TODO',
			'outputName' 	=> !empty($p['outputName']) ? $p['outputName'] 													: $srcInfo['filename'],
			'dimensions' 	=> !empty($p['dimensions']) ? $p['dimensions'] 													: array($this->width, $this->height),
			'prefix' 		=> !empty($p['prefix'])		? ( !is_array($p['prefix']) ? array($p['prefix']) : $p['prefix'] ) 	: NULL,
			'behaviour' 	=> !empty($p['behaviour'])	? $p['behaviour'] 													: 'constrain',
			'suffix' 		=> !empty($p['suffix'])		? ( !is_array($p['suffix']) ? array($p['suffix']) : $p['suffix'] ) 	: NULL,
			'quality' 		=> !empty($p['quality'])	? $p['quality'] 													: 80,
			'pngBit'		=> !empty($p['pngBit'])		? $p['pngBit'] 														: ( $this->mime === 'png' ? $this->getPngBit($p['src']) : '' ),
			'return' 		=> !empty($p['return'])		? $p['return']														: 'boolean',
		);
		
		$this->data 	= array();		
		
		// 
		$dims 			= $p['dimensions'];
		$this->params['dimensions'] = is_array($dims) && count($dims) === 2 && ( is_numeric($dims[0]) || $dims[0] === 'auto' ) && ( is_numeric($dims[1]) || $dims[1] === 'auto' ) ? array($dims) : $dims;
		
		$this->handleDimensions();

		return !empty($p['return']) && $p['return'] === 'boolean' ? true : $this->data;
	}

	## --------------------------------------------------------

	public function getMime($fileName)
	{
		$imgType = !empty($fileName) ? exif_imagetype($fileName) : false;  // 1 = IMAGETYPE_GIF, 2 = IMAGETYPE_JPEG, 3 = IMAGETYPE_PNG
		
		$exts = array('1' => 'gif', '2' => 'jpg', '3' => 'png');
		$this->mime = !empty($imgType) && isset($exts[$imgType]) ? $exts[$imgType] : null;
		
		return $this->mime;
	}

	## --------------------------------------------------------

	public function getPngBit($fileName)
	{
		$readPng =	fopen	($fileName, "rb");
		$readAlp =	fread	($readPng, 52);
		            fclose	($readPng);

		return (substr(bin2hex($readAlp),50,2) == "04" || substr(bin2hex($readAlp),50,2) == "06") ? 24 : 8;
	}

	## --------------------------------------------------------

	private function openImage($fileName)
	{
		//$type = array('jpg' => 'jpeg', 'gif' => 'gif', 'png' => 'png');
		//$img = in_array($this->mime, $type) ? call_user_func('imagecreatefrom' . $type[$this->mime], $fileName) : null;

		switch($this->mime)
		{
			case 'jpg':
				$img = @imagecreatefromjpeg($fileName);
				break;
			case 'gif':
				$img = @imagecreatefromgif($fileName);
				break;
			case 'png':
				$img = @imagecreatefrompng($fileName);
				break;
			default:
				$img = false;
				break;
		}
		
		return $img;
	}

	## --------------------------------------------------------

	public function handleDimensions()
	{
		// Shortcut for dimensions
		//$dims = $this->params['dimensions'];
		$p = $this->params;
		$dims = $p['dimensions'];

		foreach( $dims as $k => $dim )
		{
			// Iterates over himself if required
			//if ( $isDimArray ){ return $this->handleDimensions($dim); }
			
			// Otherwise, process dimensions
			// TODO: throw an error if one of the values is not a recognized one (instead of using src dimensions)
			$imgW = is_numeric($dim[0]) || $dim[0] === 'auto' ? $dim[0] : intval($dim[0]);
			$imgH = is_numeric($dim[1]) || $dim[1] === 'auto' ? $dim[1] : intval($dim[1]);

			// resize image
			$this->resizeImage($imgW, $imgH, $k);

			// make output name
			$outputFilename = ( !empty($p['prefix'][$k]) ? $p['prefix'][$k] : '' ) . $p['outputName']
							. ( !empty($p['suffix'][$k]) ? $p['suffix'][$k] : ( !empty($p['prefix'][$k]) ? '' : $this->data[$k]['width'] . 'x' . $this->data[$k]['height'] ) ); 

			// make output data
			$this->data[$k] = array(
				'src' 				=> $p['src'],
				'prefix' 			=> $p['prefix'][$k],
				'suffix' 			=> $p['suffix'][$k],
				'outputFormat' 		=> $this->mime,
				'outputDirname' 	=> $p['srcPath'],
				'outputBasename' 	=> $outputFilename . '.' . $this->mime,
				'outputFilename' 	=> $outputFilename,
				'outputExtension' 	=> $this->mime,
				//'outputPath' 	=> $p['srcPath'],
				//'outputName' 	=> $outputName,
				'dimensions' 		=> array($this->data[$k]['width'], $this->data[$k]['height']),
				'behaviour' 		=> $p['behaviour'],
				'quality' 			=> !empty($p['quality'])	? $p['quality'] 	: 80,
				'pngBit' 			=> $p['pngBit'],
				'srcPath' 			=> !empty($p['srcPath'])	? $p['srcPath'] 	: '',
				'srcFile' 			=> !empty($p['srcFile'])	? $p['srcFile'] 	: null,
			);
			// save image
			$this->saveImage($p['srcPath'] . $this->data[$k]['outputBasename']);
		}
	}

	## --------------------------------------------------------

	public function resizeImage($newWidth, $newHeight, $k)
	{
		$p = $this->params;
		$ratio 		= $this->width / $this->height;
		$ratioDest	= $newWidth / $newHeight;

		if ($p['behaviour'] === 'constrain' && ( $newWidth != 'auto' && $newHeight != 'auto' ))
		{
			$newHeight 	= $ratioDest > $ratio ? $newHeight 	: 'auto';
			$newWidth 	= $ratioDest < $ratio ? $newWidth 	: 'auto';
		}
		$newWidth 	= $newWidth === 'auto' ? ( $newHeight * $ratio ) : $newWidth;
		$newHeight 	= $newHeight === 'auto' ? ( $newWidth / $ratio ) : $newHeight;

		$this->data[$k]['width'] = round($newWidth);
		$this->data[$k]['height'] = round($newHeight);

		// *** Resample - create image canvas of x, y size
		$this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
	}

	## --------------------------------------------------------

	public function saveImage($savePath)
	{
//var_dump($savePath);
		
		switch($this->mime)
		{
			case 'jpg':
				if (imagetypes() & IMG_JPG) {
					imagejpeg($this->imageResized, $savePath, $this->params['quality']);
				}
				break;

			case 'gif':
				if (imagetypes() & IMG_GIF) {
					imagegif($this->imageResized, $savePath);
				}
				break;

			case 'png':
				// *** Scale quality from 0-100 to 0-9
				$scaleQuality = round(($this->params['quality']/100) * 9);

				// *** Invert quality setting as 0 is best, not 9
				$invertScaleQuality = 9 - $scaleQuality;

				if (imagetypes() & IMG_PNG) {
					//if ($this->params['pngBit'] == 8) {imagetruecolortopalette($this->imageResized, false, 255);}
					imagepng($this->imageResized, $savePath, $invertScaleQuality);
				}
				break;

			// ... etc

			default:
				// *** No extension - No save.
				break;
		}

		imagedestroy($this->imageResized);
	}

	## --------------------------------------------------------


	/*
	private function getOptimalCrop($newWidth, $newHeight)
	{

		$heightRatio = $this->height / $newHeight;
		$widthRatio  = $this->width /  $newWidth;

		if ($heightRatio < $widthRatio) {
			$optimalRatio = $heightRatio;
		} else {
			$optimalRatio = $widthRatio;
		}

		$optimalHeight = $this->height / $optimalRatio;
		$optimalWidth  = $this->width  / $optimalRatio;

		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	## --------------------------------------------------------

	private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
	{
		// *** Find center - this will be used for the crop
		$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
		$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

		$crop = $this->imageResized;
		//imagedestroy($this->imageResized);

		// *** Now crop from center to exact requested size
		$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
		imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
	}
	*/
}
?>
