<?php

class MathCaptcha
{	
	public function create()
	{				
		$nb1 							= rand(10,90);
		$nb2 							= rand(1,9);
		$captchaOperation 				= $nb1 . " + " . $nb2 . " = ";
		$captchaResult					= $nb1 + $nb2;
		
		//session_start();
		$_SESSION['captchaOperation'] 	= $captchaOperation;
		$_SESSION['captchaResult'] 		= $captchaResult;
	
//var_dump($_POST);
//var_dump(session_id());
//var_dump($_SESSION['captchaOperation']);
//var_dump($_SESSION['captchaResult']);

		return $captchaOperation;
	}
	
	
};

?>