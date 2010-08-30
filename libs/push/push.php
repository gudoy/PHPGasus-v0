<?php

// Passphrase for the private key (ck.pem file)
$pass 			= 'yourpassphrasehere';

// Get the parameters from http get or from command line
$deviceToken 	= 'f91b69d3 2d9c6175 1ae92fcf 24e66f38 4cace4a1 58b72776 0dd2261b 45189b1c';  	// Masked for security reason
$message 		= 'Test message. It works! Or not!'; 
$badge 			= 0; 
$sound 			= 'received5.caf'; 

// Construct the notification payload
$body 			= array();
$body['aps'] 	= array('alert' => $message);

if ( $badge ) { $body['aps']['badge'] = $badge; } 
if ( $sound ) { $body['aps']['sound'] = $sound; }

# End of Configurable Items

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', 'mynewproject.pem');  
// assume the private key passphase was removed.
// stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);

$fp = stream_socket_client(_APP_PUSH_GATEWAY, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);

if ( !$fp ) { print "Failed to connect $err $errstr\n"; return; }
else 		{ print "Connection OK\n"; }

$payload 	= json_encode($body);
$msg 		= chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;

print "sending message :" . $payload . "\n";

fwrite($fp, $msg);
fclose($fp);

?>