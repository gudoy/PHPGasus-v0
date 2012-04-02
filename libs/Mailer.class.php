<?php
//class_exists('View') 	|| require(_PATH_LIBS . 'View.class.php');

class Mailer extends View
{
	public $from 		= _APP_OWNER_MAIL;
	public $success 	= null;
	public $errors 		= null;
	
	public $headers 	= array();
	
	public function __construct(&$application, $options = array())
	{
		$o 					= $options;
		$this->usePEARmail 	= !empty($o['usePEARmail']);
		
		parent::__construct($application);
		
		if ( $this->usePEARmail ) { $this->initPEAR(); return; }
		
		return $this;
	}
	
	public function initPEAR()
	{
		# http://pear.php.net/manual/en/package.mail.mail.php
		
		// Load PEAR Mail class
		error_reporting( E_ALL & ~( E_NOTICE | E_STRICT | E_DEPRECATED ) );
		include('Mail.php');
		
		$this->PEARbackend 	= 'smtp';
		
		$this->smtpParams = array(
			'host' 			=> _SMTP_HOST,
			'port' 			=> _SMTP_PORT,
			'auth' 			=> true,
			'username' 		=> _SMTP_USER,
			'password' 		=> _SMTP_PASS,
			'localhost' 	=> _SMTP_USER,
			'timeout' 		=> _SMTP_TIMEOUT,
			'persist' 		=> _SMTP_PERSIST_CONNEXION,
			'pipelining' 	=> _SMTP_PIPELINING,
			'debug' 		=> _SMTP_DEBUG,
		);
		$this->Mail = &Mail::factory($this->PEARbackend, $this->smtpParams);
				
/*				
if ( PEAR::isError($this->Mail) )
{

var_dump($this->smtpParams);

var_dump($result->getMessage());
var_dump($result->getCode());
var_dump($result->getMode());
var_dump($result->getCallback());
var_dump($result->getDebugInfo());
var_dump($result->getType());
var_dump($result->getUserInfo());
var_dump($result);
}*/

	}
	
	public function fetch($options = array())
	{
		//$o = $options;
		//$o['data'] = !empty($o['data']) ? $o['data'] : null;
		$o = array_merge(array('data' => null), $options);
		
		if ( empty($o['template']) ) { return; }
		
		$this->configSmarty();

		//$this->prepare();
		
		//$this->Smarty->caching = false;
		$this->Smarty->assign(array('data' => $o['data']));
		
		return $this->Smarty->fetch($o['template']);
	}
	
	public function render()
	{	
		return $this;
	}
	
	public function setParams($params = array())
	{
		$o = $params;
		
		$this->from 		= !empty($o['from']) ? $o['from'] : null;
		$this->replyTo 		= !empty($o['replyTo']) ? $o['replyTo'] : $this->from;
		$this->subject 		= !empty($o['subject']) ? $o['subject'] : $this->subject;
		$this->content 		= !empty($o['content']) ? $o['content'] : null;
		$this->to 			= !empty($o['to']) ? $o['to'] : $this->to;
		$this->cc 			= !empty($o['cc']) ? $o['cc'] : null;
		$this->bcc 			= !empty($o['bcc']) ? $o['bcc'] : null;
		//$this->cci 			= !empty($o['cci']) ? $o['cci'] : null;
		$this->format 		= !empty($o['format']) ? $o['format'] : 'text';
		$this->alternative 	= !empty($o['alternative']) ? $o['alternative'] : '';
		
		$this->returnPath 	= !empty($o['returnPath']) ? $o['returnPath'] : $this->from;
		$this->returnPath 	= ($pos = strpos($this->returnPath, '<')) && $pos !== false ? str_replace(array('<','>'), '', substr($this->returnPath, $pos)) : $this->returnPath;
	}
	
	public function setHeaders()
	{
		$this->headers = array(
			'From' 							=> $this->from,
			'Delivered-To' 					=> $this->to,
			'Cc' 							=> $this->cc,
			'Bcc' 							=> $this->bcc,
			//'Cci' 							=> $this->cci,
			'Reply-To' 						=> $this->replyTo,
			//'Return-Path' 					=> $this->from,
			'Return-Path' 					=> $this->returnPath,
			'Subject' 						=> $this->subject,
			'MIME-Version' 					=> '1.0',
			'Content-Type' 					=> $this->format === 'html' ? ' text/html; charset=UTF-8' : ' text/plain; charset=UTF-8',
			//'Content-Transfer-Encoding' 	=> '8bit',
			'X-Mailer' 						=> 'PHP/' . phpversion()
		); 
	}
	
	public function writeHeaders()
	{
		
		$eol = "\n";
		$headers = '';
		
		foreach ( (array) $this->headers as $header => $value )
		{
			// TODO: will there be params whose value could be 0
			// If yes, replace empty with isset($value) && !is_null($value) && $value != ''
			$headers .= !empty($value) ? $header . ':' . $value . $eol : '';	
		}
		
		return $headers;
	}
	
	
	public function writeBody()
	{
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
		
	
//var_dump(mb_detect_encoding($o['content']));
	}
	

	public function send($options = array())
	{
		$o 					= $options;
		$this->success 		= false;
		$this->errors 		= array();
		
		$this->setParams($o);
		
		// If no recipient has been passed, do not continue
		//if ( empty($o['to']) || empty($o['subject']) || empty($o['content']) ){ return; }
		if ( !$this->to || !$this->subject || !$this->content ){ return; }
		
		$this->setHeaders();
		//$headers = $this->writeHeaders();
	
		if ( $this->usePEARmail )
		{			
			$result 		= $this->Mail->send($this->to, $this->headers, $o['content']);
			$this->success 	= $result === true;
			$this->errors 	= !$this->success ? $result : array();
			
			// TODO: handle PEAR error

/*
if ( PEAR::isError($result) )
{

var_dump($this->smtpParams);
var_dump($this->headers);

var_dump($result->getMessage());
var_dump($result->getCode());
var_dump($result->getMode());
var_dump($result->getCallback());
var_dump($result->getDebugInfo());
var_dump($result->getType());
var_dump($result->getUserInfo());
var_dump($result);
}*/
		}
		else
		{
			$this->success = mail($this->to, '=?UTF-8?B?'.base64_encode($this->subject).'?=', $o['content'], $this->writeHeaders());
		}
		
		return $this;
	}	
	
}
?> 