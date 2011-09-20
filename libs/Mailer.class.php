<?php
//class_exists('View') 	|| require(_PATH_LIBS . 'View.class.php');

class Mailer extends View
{
	public $from 		= _APP_OWNER_MAIL;
	public $success 	= null;
	public $errors 		= null;
	
	public function __construct(&$application)
	{
		parent::__construct($application);
		
		$this->configSmarty();
		
		return $this;
	}
	
	public function fetch($options = array())
	{
		$o = $options;
		$o['data'] = !empty($o['data']) ? $o['data'] : null;
		
		if ( empty($o['template']) ) { return; }

		//$this->prepare();
		
		//$this->Smarty->caching = false;
		$this->Smarty->assign(array('data' => $o['data']));
		
		return $this->Smarty->fetch($o['template']);
	}
	
	public function render()
	{
		
		
		return $this;
	}

	public function send($options = array())
	{
		$o 					= $options;
		$this->success 		= false;
		$this->errors 		= array();
		$this->from 		= !empty($o['from']) ? $o['from'] : $this->from;
		
		// If no recipient has been passed, do not continue
		if ( empty($o['to']) || empty($o['subject']) || empty($o['content']) ){ return; }
		
		$this->subject 		= !empty($o['subject']) ? $o['subject'] : $this->subject;
		$this->to 			= !empty($o['to']) ? $o['to'] : $this->to;
		$this->format 		= !empty($o['format']) ? $o['format'] : 'text';
		$this->alternative 	= !empty($o['alternative']) ? $o['alternative'] : '';
		
		// Mail headers
		$eol = "\n";
		$headers = "From:" . $this->from . "\n";
		//$headers .= "To:" . $this->to . "\n";
		$headers .= "Delivered-to:" . $this->to . "\n";
		$headers .= (!empty($o['cc'])) ? "Cc:" . $o['cc'] . "\n" : '';
		$headers .= (!empty($o['cci'])) ? "Cci:" . $o['cci'] . "\n" : '';
		$headers .= "Reply-To:" . $this->from . "\n";
		$headers .= "Return-Path:" . $this->from . "\n";
		//$headers .= "Content-type: text/html; charset=utf-8 MIME-Version: 1.0 \n";
		//$headers .= "Content-Type: text/plain; charset=UTF-8 MIME-Version: 1.0 \n";
		//$headers .= "Content-Type: text/plain; charset=UTF-8 MIME-Version: 1.0 \n";
		
		/*
		if ( !empty($this->alternative) )
		{
			//$randomHash = md5(time());
			$randomHash = md5( !empty($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time() );
			
			$headers .= "Content-Type: multipart/alternative; boundary=\"PHP-alt-" . $randomHash . "\n";
			ob_start();
			echo "--PHP-alt-" . $randomHash;
			echo "Content-Type: text/plain; charset=UTF-8" . $eol;
			echo "Content-Transfer-Encoding: 7bit\n" . $eol;
			echo $this->alternative;  
		}
		
		echo "--PHP-alt-" . $randomHash;
		echo "Content-Type: text/html; charset=UTF-8";
		echo "Content-Transfer-Encoding: 7bit";
		echo $o['content'];
		echo "--PHP-alt-" . $randomHash . '--';  
		
		//copy current buffer contents into $message variable and delete current output buffer
		$message = ob_get_clean();
		
echo $message;
*/		
		
		$headers .= $this->format === 'html'
					? 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n"
					: 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";

		//$headers .= "Content-Transfer-Encoding: 8bit" . $eol; 
		$headers .= "X-Mailer:PHP/" . phpversion() . "\n" ;
		//$headers .= "Date:" . date();
	
//var_dump(mb_detect_encoding($o['content']));
	
		//$this->success = mail($this->to, $this->subject, $o['content'], $headers);
		$this->success = mail($this->to, '=?UTF-8?B?'.base64_encode($this->subject).'?=', $o['content'], $headers);
		//$this->success = mail($this->to, '=?UTF-8?B?'.base64_encode($this->subject).'?=', $message, $headers);
		
		return $this;
	}	
	
}
?> 