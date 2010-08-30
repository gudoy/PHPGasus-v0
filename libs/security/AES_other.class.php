<?php


// !!!!!!! Gudoy: did not found same results with Basile 



class AES
{
    var $key = NULL;
    var $iv = NULL;
    var $iv_size = NULL;
		
	private static $_instance;
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}

	public function __construct($key = "")
    {
		$this->key = ($key != "") ? $key : "";
		
		$this->algorithm = MCRYPT_RIJNDAEL_128;
		$this->mode = MCRYPT_MODE_CBC;
		
		$this->iv_size = mcrypt_get_iv_size($this->algorithm, $this->mode);
		$this->iv = mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
    }

	public function encrypt($data, $key = '')
    {
    	$this->key = $key;
		
		$size = mcrypt_get_block_size($this->algorithm, $this->mode);
		$data = $this->pkcs5_pad($data, $size);
		
		return base64_encode(mcrypt_encrypt($this->algorithm, $this->key, $data, $this->mode, $this->iv));
    }

	public function decrypt($data, $key = '')
    {
    	$this->key = $key;
		
		return $this->pkcs5_unpad(rtrim(mcrypt_decrypt($this->algorithm, $this->key, base64_decode($data), $this->mode, $this->iv)));
    }

	public function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
		
        return $text . str_repeat(chr($pad), $pad);
    }

	public function pkcs5_unpad($text)
    {
            $pad = ord($text{strlen($text)-1});
            if ($pad > strlen($text)) return false;
            if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
            return substr($text, 0, -1 * $pad);
    }
}
 
?>