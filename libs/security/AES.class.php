<?php

class AES
{
	private static $_instance;
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	public function encrypt($msgStr, $key, $iv = null)
	{
		$iv 			= !$iv ? $key : $iv;
		
		if ( strlen($msgStr) % 16 !== 0)
		{
			
	        $paddChar 	= chr(16-(strlen($msgStr) % 16));
	        $paddLen 	= 16 * (1 + floor(strlen($msgStr) / 16));
	        $msgStr 	= str_pad($msgStr, $paddLen, $paddChar);
			/*
			$pad = 16 - (strlen($msgStr) % 16);
			$msgStr = $msgStr . str_repeat(chr($pad), $pad);
			*/	
		}

		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $msgStr, MCRYPT_MODE_CBC, $iv);
		
		return bin2hex($crypttext);
	}
	
	
	public function hex2asc($hex)
	{
		return pack('H*', $hex);
	}
	
	public function hexstr($hexstr)
	{
		$hexstr = str_replace(' ', '', $hexstr);
		$hexstr = str_replace('\x', '', $hexstr);
		$retstr = pack('H*', $hexstr);
		
		return $retstr;
	}
	
	public function decrypt($encMsgStr, $key, $iv = null)
	{
		$key 			= $key;
		$iv 			= !$iv ? $key : $iv;
		$encMsgStr 		= self::hex2asc($encMsgStr);
		
		$decrypttext 	= mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encMsgStr, MCRYPT_MODE_CBC, $iv);
		/*
		echo $decrypttext . '<br/>';
		echo rtrim($decrypttext, "\0\1") . '<br/>'; // remove SOH (Start of heading) chars
		echo rtrim($decrypttext, "\0\3") . '<br/>'; // remove ETX (End of Text) chars
		echo rtrim($decrypttext, "\0\4") . '<br/>'; // remove EOT (End of Transmission) chars
		echo str_replace("\x0", '', $decrypttext); 
		 */
		/*
		$decrypttext 	= str_replace('\0\1', '', $decrypttext);
		$decrypttext 	= str_replace('\0\2', '', $decrypttext);
		$decrypttext 	= str_replace('\0\3', '', $decrypttext);
		$decrypttext 	= str_replace('\0\4', '', $decrypttext);
		$decrypttext 	= str_replace('\0\6', '', $decrypttext);
		$decrypttext 	= str_replace('\x0', '', $decrypttext);
		*/

		$decrypttext = trim($decrypttext, "\x00..\x1F");
		
		return $decrypttext;
	}
}

?>