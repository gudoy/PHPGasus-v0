<?php

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

class RSSimporter extends Application
{
	public $data 	= null;
	public $success = null;
	public $errors 	= null;
	
	public function index($options = array())
	{		
		$o = $options;
		
		$this->feedUrl = !empty($o['url']) ? $o['url'] : null;
		
		// TODO: throw 'no feed url provided error' ???
		if ( empty($this->feedUrl) ){ return $this; }  
		
		return $this->launch($o);
	}
	
	public function launch($options = array())
	{
		$this->success 	= false;
		$this->errors 	= array();
		
		// Convert the RSS into a PHP object
		$rssObj 		= $this->XML2Array($this->feedUrl, false, array('type' => 'rss'));
		
		// Load proper controllers and instanciate them
		$this->requireControllers(array('CEntries','CMedias'));
		$CEntries 		= new CEntries();
		$CMedias 		= new CMedias();
		$entries 		= array();
		$medias 		= array();
		
		// Loop over the items
		$items 			= !empty($rssObj['channel']['item']) ? $rssObj['channel']['item'] : array();
		foreach ( $items as $item )
		{
			$title 		= !empty($item['title']) ? $item['title'] : '';
			$pubDate 	= !empty($item['pubDate']) ? strtotime($item['pubDate']) : '';
			$desc 		= !empty($item['description']) ? $item['description'] : '';
			$author 	= !empty($item['author']) ? $item['author'] : '';
			$link 		= !empty($item['link']) ? $item['link'] : '';
			//$slug 		= substr($this->slugify($title), 0,63);
			$slug        = substr(Tools::slugify($title), 0,63);
			
			// Do no import already imported entries
			$entryInDB = $CEntries->retrieve(array('by' => 'admin_title', 'values' => $slug));
			if ( !empty($entryInDB) ) { $this->success = true; continue; }
			
			// Get images tags then remove them from the description
			preg_match_all('/<img.*\\>/Uis', $desc, $matches);
			$desc 	= preg_replace('/<img.*\\>/Uis', '', $desc);
			
			$entry = array(
				'admin_title' 		=> $slug,
				'type' 				=> 'rssItem',
				'title_FR' 			=> $title,
				'text_FR' 			=> $desc,
				'publication_date' 	=> $pubDate,
				'origine_url' 		=> $link,
				'author_name' 		=> $author,
			);
			
			// Insert the entry into the db and get the created id
			$_POST = array();
			foreach ( $entry as $k => $v ) { $_POST['entry' . ucfirst($k)] = $v; }
			$eid = $CEntries->create(array('returning' => 'id'));
			$this->data['entries'][] = $entry;
			$_POST = null;
			
			// Loop over the found img tags
			foreach ( $matches[0] as $item )
			{
				// Get some attributes values
				$src 		= preg_replace('/.*src=["\']?([^"\'\s]*)["\'\s].*/is','$1',$item);
				$width 		= strpos($item, 'width') !== false ? preg_replace('/.*width=["\']?([^"\'\s]*)["\'\s].*/is','$1',$item) : null;
				$height 	= strpos($item, 'height') !== false ? preg_replace('/.*height=["\']?([^"\'\s]*)["\'\s].*/is','$1',$item) : null;
				$alt 		= strpos($item, 'alt') !== false ? preg_replace('/.*alt=["\']?([^"\'\s]*)["\'\s].*/is','$1',$item) : '';

				// Do not continue with the following tag if an src has not been found
				//if ( empty($src) || empty($eid) ){ continue; }
				if ( empty($src) ){ continue; }
				
				$media 	= array(
					'admin_title' 	=> 'rssItem' . $eid . '_' . preg_replace('/.*\/(.*)/','$1',$src),
					'title_FR' 		=> $alt,
					'url' 			=> $src,
					'width' 		=> $width,
					'height' 		=> $height,
					'entries_id' 	=> $eid,
				);
				
				// Insert the media into the db and get the created id
				$_POST = array();
				foreach ( $media as $k => $v ) { $_POST['media' . ucfirst($k)] = $v; }
				$mid = $CMedias->create(array('returning' => 'id'));
				$this->data['medias'][] = $media;
				$_POST = null;
			}
			
			if ( !empty($this->data['entries']) ){ $this->success = true; } 
			
//var_dump($entries);
//var_dump($medias);
		}
//var_dump(@$this->data['entries']);
//var_dump(@$this->data['medias']);
//$this->dump($this->data);
	}
}

?>