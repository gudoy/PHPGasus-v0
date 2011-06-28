<?php

class ApplePush //extends Application
{
	public $data = array();
	public $success = false;
	public $errors = array();
	
	public function messagePusher($params = array())
	{
		
		$p 			= $params; 											// Shortcut for params
		$pass 		= ''; 										// Passphrase for the private key (ck.pem file)
		$env 		= $p['env'] === 'prod' ? 'PROD' : 'TEST';  			// Get the passed env of default it to 'TEST'
		$pushAddr 	= constant('_APP_IPHONE_PUSH_GATEWAY_' . $env);		// Get push gateway
		
		// Get the parameters from http get or from command line
		//$p['deviceToken'] 	= 'f91b69d3 2d9c6175 1ae92fcf 24e66f38 4cace4a1 58b72776 0dd2261b 45189b1c';  	// Masked for security reason
		//$p['message'] 		= 'Soundwalk message test. Ca marche! Ou pas!'; 
		$p['badge'] 		= 0; 
		$p['sound'] 		= 'received5.caf';
		
		// Construct the notification payload
		// TODO: limit msg length (playload limited to 256 bytes)
		$body 			= array(
			'aps' => array('alert' => $p['message'])
		);
		
		if ( $p['badge'] ) { $body['aps']['badge'] = $p['badge']; } 
		if ( $p['sound'] ) { $body['aps']['sound'] = $p['sound']; }
		
		$ctx = stream_context_create();
		//stream_context_set_option($ctx, 'ssl', 'local_cert', _PATH_LIBS . 'push/soundwalk.pem');  
		//stream_context_set_option($ctx, 'ssl', 'local_cert', _PATH_LIBS . 'push/' . _APP_NAME . '.' . ( $env === 'PROD' ? 'prod' : 'dev' ) . '.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert', _PATH_LIBS . 'push/apns-' . ( $env === 'PROD' ? 'prod' : 'dev' ) . '.pem');
		// assume the private key passphase was removed.
		// stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
		
		$fp = stream_socket_client($pushAddr, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		
		if ( !$fp ) { print "Failed to connect $err $errstr\n"; return; }
		//else 		{ print "Connection OK\n"; }
		else 		{ $this->success = true; }
		
		$payload 	= json_encode($body);
		//$msg 		= chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $p['deviceToken'])) . pack("n",strlen($payload)) . $payload;
		$msg 		= chr(0) . @pack("n",32) . @pack('H*', str_replace(' ', '', $p['deviceToken'])) . @pack("n",strlen($payload)) . $payload;

		//echo "sending message :" . $payload . "\n";
		//echo "Push message sent to: " . $p['deviceToken'] . '<br/>';
		
		fwrite($fp, $msg);
		fclose($fp);
		
		$this->data['sentpushs'][] = array('token' => $p['deviceToken']);
		
		return $this;
	}
}

?>