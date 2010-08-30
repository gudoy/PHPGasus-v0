<?php

class VAdmin extends AdminView
{
	public function __construct()
	{
		$this->authLevel = array('god','superadmin','admin','contributor');
		
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}

	public function index($options = null)
	{
		$this->data['view'] = array_merge((array) @$this->data['view'], array(
			//'name' 		=> 'admin',
			'name' 		=> 'adminHome',
			'js' 		=> 'adminHome',
			'method' 	=> __FUNCTION__,
			'template' 	=> 'specific/pages/admin/dashboard/index.tpl',
			'errorsBlock' 	=> false,
		));
		
		// Loop over the resources
		$r = $this->data['current']['groupResources'];
		foreach ($r as $key => $val)
		{
			$name = is_numeric($key) ? $val : $key;
			
			// Get the resource meta
			$m = $this->data['metas'][$name]; 
			
			// Load its controller
			class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
			
			// Instanciate it
			$resController = new $m['controllerName']();
			
			//$this->data['meta'][$name] 	= $this->meta($name);
			$this->data['total'][$name] = $resController->index(array('mode' => 'count'));
			
		}
		
		$this->usersStats();
		
		//$this->handleMessagePusher();
		
//$this->dump($this->data);
			
		$this->render();
	}
	
	
	public function handleRSSimporter()
	{
		if ( !empty($_POST['rssimporter']) )
		{
			// Load RSS Importer lib
			$this->requireLibs(array('RSSimporter' => 'importers/'));
			$RSSimporter = new RSSimporter();
			$import 								= $RSSimporter->index(array('url' => _URL_ARC_RSS_FEED));
			//$this->data['imported'] = $RSSimporter->index(array('url' => _URL_ARC_RSS_FEED))->data;
			
			$this->data['rssimporter']['success'] 	= $RSSimporter->success;
			
			if ( $this->data['rssimporter']['success'] ){ $_POST = null; }
		}
	}
	
	
	public function usersStats()
	{
		$CSessions = new CSessions();
		$this->data['usersStats']['connected'] = $CSessions->index(array(
			'sortBy' 	=> 'expiration_time',
			'orderBy' 	=> 'DESC',
			'conditions' 	=> array(
				array('update_date', '>', ("FROM_UNIXTIME('" . (time() - _APP_SESSION_DURATION) . "')")),
				//array('expiration_time', '>', ("FROM_UNIXTIME('" . time() . "')")),
			)
		));
		$userIds 						= $CSessions->values('users_id');
		$this->data['connectedUsers'] 	= CUsers::getInstance()->index(array('values' => $userIds, 'reindexby' => 'id')); 
	}
	
	
	public function related($checkAgainstResource = '', $options = null)
	{
		$c 				= strtolower($checkAgainstResource); 			// Shortcut for resource to check against
		$filterValue 	= $this->options['values'];  
		$resources 		= $this->dataModel['resourcesFields']; 		// Shortcut for resources
		$dmR 			= $this->dataModel['resources'];
		$this->data 	= array(
			'related' 			=> array(),
			'siblings' 			=> array(),
		);
		
		// Loop over the resources
		foreach ((array) $resources as $rName => $cols)
		{			
			// Loop over their colums
			foreach ((array) $cols as $cName => $props)
			{
				if ( $rName === $c && !empty($props['relResource']) )
				{
					$m = $this->meta($props['relResource']);
					
					// Load its controller
					class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
					
					// Instanciate it
					$ctrlr = new $m['controllerName']();
					
					// List the fields whe have to get
					$fields2get = array($props['relField'], $cName);
					
					$this->data['siblings'][$props['relResource']] = array(
						'meta' 		=> $m,
						'relOn' 	=> $cName,
						'relType' 	=> 'sibling',
						'items' => $ctrlr->index(array('values' => !empty($_GET[$cName]) ? $_GET[$cName] : null, )),
					);
				}
				
				// Only process relResource properties
				if ( empty($props['relResource']) || $props['relResource'] !== $c ) { continue; }
				
				$m = $this->meta($rName);

				// Load its controller
				class_exists($m['controllerName']) || require(_PATH_CONTROLLERS . $m['controllerPath']);
				
				// Instanciate if
				$ctrlr = new $m['controllerName']();

				// List the fields whe have to get
				$fields2get = array($props['relField'], $cName);
				
				// If the default name field for this resource should be gotten via a JOIN, we have to add the column on which the join is done
				if ( !empty($m['defaultNameField']) && !isset($resources[$rName][$m['defaultNameField']]) )
				{					
					foreach ((array) $cols as $key => $val)
					{
						if ( empty($val['relGetAs']) || $val['relGetAs'] !== $m['defaultNameField']) { continue; }
								
						array_push($fields2get, $key);
					}
				}
				else { array_push($fields2get, $m['defaultNameField']); } 
				
				// Then build the output data
				$this->data['related'][$rName] = array(
					'meta' => $m,
					'relOn' => $cName,
					'relType' => 'child',
					'items' => $ctrlr->index(array(
						'values' 	=> $filterValue, 
						'by' 		=> !empty($filterValue) ? $cName : null,
						'getFields' => $fields2get,
					)),
				);
			}
		}

		$this->data['related'] += $this->data['siblings'];
		unset($this->data['siblings']);

		$this->render(__FUNCTION__);
	}


	public function handleMessagePusher()
	{
		// Since we can send a lot of messages, we need to increase the execution time for the script
		ini_set('max_execution_time','3600');

		// Get the entries			
		$this->data['entries'] = Centries::getInstance()->index(array('reindexby' => 'id'));
		
		if ( !empty($_POST['messagePusher']) )
		{
			$push 	= array(
				'msgId' 	=> filter_input(INPUT_POST, 'pushmessageId', FILTER_SANITIZE_NUMBER_INT),
				'dvcIds' 	=> !empty($_POST['deviceIds']) ? explode(',', filter_input(INPUT_POST, 'deviceIds', FILTER_SANITIZE_STRING)) : null,
				'custMsg' 	=> filter_input(INPUT_POST, 'pushmessageText', FILTER_SANITIZE_STRING), 
				'env' 		=> filter_input(INPUT_POST, 'environment', FILTER_SANITIZE_STRING),
			);
			
			// If no message has been selected/provided, do not continue
			if 		( empty($push['msgId']) && empty($push['custMsg']) ){ $this->data['errors'][] = 20056; }
			
			// Otherwise
			else
			{
				// If a custome message has been provided, use id. Otherwise, get the selected message
				$push['message'] 	= !empty($push['custMsg']) ? $push['custMsg'] : $this->data['entries'][$push['msgId']]['text_FR'];
				
				// If devices ids have been passed, get their tokens id
				// Otherwise, just get all the token ids registered 
				$opts 				= !empty($push['dvcIds']) ? array('conditions' => array('device_id' => $push['dvcIds'])) : array();
				
				// Get all token registered for this application
				$registrations 		= CPushregistrations::getInstance()->index($opts);

				// Loop over the push registrations for the proper application
				foreach ((array) $registrations as $item)
				{
					// Try to get the user language if it as been provided at the registration, otherwise, use 'en' as the default one
					//$lg = !empty($item['language']) && in_array($item['language'], array('fr','en')) ? $item['language'] : 'en';
					
					//$this->messagePusher( array('env' => $push['env'], 'deviceToken' => $item['token'], 'message' => $push['message']) );
				}
				
				$this->data['messagepusher']['success'] = true;
			}
		}
//$this->dump($this->data);
		
		return $this;
	}
	
	
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
		$body 			= array(
			'aps' => array('alert' => $p['message'])
		);
		
		if ( $p['badge'] ) { $body['aps']['badge'] = $p['badge']; } 
		if ( $p['sound'] ) { $body['aps']['sound'] = $p['sound']; }
		
		$ctx = stream_context_create();
		//stream_context_set_option($ctx, 'ssl', 'local_cert', _PATH_LIBS . 'push/soundwalk.pem');  
		stream_context_set_option($ctx, 'ssl', 'local_cert', _PATH_LIBS . 'push/apns_' . ( $env === 'PROD' ? 'prod' : 'dev'  ) . '.pem');
		// assume the private key passphase was removed.
		// stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
		
		$fp = stream_socket_client($pushAddr, $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
		
		if ( !$fp ) { print "Failed to connect $err $errstr\n"; return; }
		//else 		{ print "Connection OK\n"; }
		
		$payload 	= json_encode($body);
		//$msg 		= chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $p['deviceToken'])) . pack("n",strlen($payload)) . $payload;
		$msg 		= chr(0) . @pack("n",32) . @pack('H*', str_replace(' ', '', $p['deviceToken'])) . @pack("n",strlen($payload)) . $payload;

		
		// echo "sending message :" . $payload . "\n";
		//echo "Push message sent to: " . $p['deviceToken'] . '<br/>';
		
		fwrite($fp, $msg);
		fclose($fp);
		
		$this->data['sentpushs'][] = array('token' => $p['deviceToken']);
		
		return $this;
	}


	
};

?>