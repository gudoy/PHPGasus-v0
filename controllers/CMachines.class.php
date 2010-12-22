<?php

class CMachines extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__));
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
    
    public function import()
    {
        ini_set('max_execution_time',900);
        ini_set('memory_limit', '512M');
        
        $t1         = microtime(true);
        $this->lb   = '<br/>';
        
        $srcFilePath = _URL_PUBLIC . 'FUSION.ini.txt';
        //$srcFilePath = _PATH_PUBLIC . 'machines_log';
        //$srcFilePath = _PATH_PUBLIC . 'machines_log - Copie';
        
//$this->dump($srcFilePath);
        
        // Opens the file and lock it (prevents other process to access it)
        $handle         = fopen($srcFilePath, 'r');
        
        // Do not continue if the file could not be open
        if ( !is_resource($handle) ){ return $this; }
             
        flock($handle, LOCK_EX);
        
        $rowNb                  = 1;
        $imported               = 0;
        $updated                = 0;
        //$this->data           = array();
        $csvModel               = null;
        $maildmn                = '@' . 'photomaton.com';
        
        // Instanciate proper controllers
        foreach ( array('machines','users','groups','usersgroups','tasks') as $item)
        {
            $cname  = 'C' . ucfirst($item);
            $$cname = new $cname();
        }

        // Create a task an get the task id
        $task = array(
            'admin_title'           => 'machinesImport' . strftime('%d-%m-%y-%Hh%Mm%Ss', $_SERVER['REQUEST_TIME']), 
            'type'                  => 'machinesImport',
            'creation_date'         => $_SERVER['REQUEST_TIME'],
        );
        $_POST = array();
        foreach ( $task as $k => $v ){ $_POST['task' . ucfirst($k)] = $v; }
        $tId = $CTasks->create(array('returning' => 'id'));
        $_POST = null;
        
        // Get groups
        $groups                 = $CGroups->index(array('reindexby' => 'name', 'isUnique' => 1));
        
//var_dump($groups);

        // As long as we find a row to parse
        while ( ($row = fgets($handle) ) !== false)
        {
            // Do not continue if the row is empty
            if ( empty($row) ){ continue; }
            
            // Remove "" wrapping string values
            $row = str_replace('"','', $row);
            
//if ( $rowNb > 10000 ){ break; }
        
            // expects 38 columns
            $xpectedCount       = 38;
            $tmpData            = explode(";", substr($row, 0, -1));
            
            // Skip row if it does not have the expected columns count
            if ( count($tmpData) !== $xpectedCount )
            {
                $this->logError('Wrong data count for line ' . (string) $rowNb . '. Expected count: ' . $xpectedCount . ' ');
                $this->logError('Raw data:' . (string) $row);
                $rowNb++;
                continue;
            }
            
//var_dump($tmpData);
            
            // Remove "" wrapping string values
            //foreach ( $tmpData as $k => $v ){ $tmpData[$k] = is_string($v) ? str_replace(array('"'), array(''), $v) : $v; }
            
//var_dump($tmpData);

            // Handle the commercial
            if ( !empty($tmpData[29]) )
            {
                $ccial      = array(
                    'first_name'    => strtolower(preg_replace('/(.*)\s(.*)/', '$2', $tmpData[29])),
                    'last_name'     => strtolower(preg_replace('/(.*)\s(.*)/', '$1', $tmpData[29])),
                );
                $u          = &$ccial;              // Shortcut for the user data
                $u          += array(
                    'name'      => $u['first_name'] . ' ' . $u['last_name'],
                    'email'     => !empty($u['first_name']) && !empty($u['last_name']) ? $u['first_name'][0] . $u['last_name'] . $maildmn : null,
                    'password'  => md5(time()),
                );
                
                // Try to find the user in the db
                $uid = $CUsers->retrieve(array(
                    'getFields'     => 'id',
                    'conditions'    => array('first_name' => $u['first_name'], 'last_name' => $u['last_name']),
                    'limit'         => 1,
                ));
                
//$this->dump($uid);                
//$this->dump('commercial id:' . $uid);
                
                // If the user id has not beed found, create it
                if ( empty($uid) )
                {
                    // Create the user
                    $_POST      = array();
                    foreach ($u as $k => $v) { $_POST['user' . ucfirst($k)] = $v; }
                    $uid    = $CUsers->create(array('returning' => 'id'));
                    
                    // Insert him into the proper groups
                    $_POST      = array();
                    $ugp        = array( 'user_id' => $uid, 'group_id' => $groups['commercials']['id']);
                    foreach ($ugp as $k => $v) { $_POST['usersgroup' . ucfirst($k)] = $v; }
                    $CUsersgroups->create();
                    
                    $_POST      = array();
                    $ugp        = array( 'user_id' => $uid, 'group_id' => $groups['admins']['id']);
                    foreach ($ugp as $k => $v) { $_POST['usersgroup' . ucfirst($k)] = $v; }
                    $CUsersgroups->create();
                }
                
                $ccialid = $uid;
            }
            
            // Handle the technician
            if ( !empty($tmpData[16]) || !empty($tmpData[17]) )
            {
                $techn      = array(
                    'first_name'    => strtolower(preg_replace('/(.*)\s(.*)/', '$2', $tmpData[17])),
                    'last_name'     => strtolower(preg_replace('/(.*)\s(.*)/', '$1', $tmpData[16])),
                );
                $u          = &$techn;              // Shortcut for the user data
                $u          += array(
                    'name'          => $u['first_name'] . ' ' . $u['last_name'],
                    'email'         => !empty($u['first_name']) && !empty($u['last_name']) ? $u['first_name'][0] . $u['last_name'] . $maildmn : null,
                    'password'      => md5(time()),
                    'sector_code'   => $tmpData[14],
                );
                
                // Try to find the user in the db
                $uid = $CUsers->retrieve(array(
                    'getFields'     => 'id',
                    'conditions'    => array('first_name' => $u['first_name'], 'last_name' => $u['last_name']),
                    'limit'         => 1,
                ));
                
                // If the user id has not beed found, create it
                if ( empty($uid) )
                {
                    // Create the user
                    $_POST      = array();
                    foreach ($u as $k => $v) { $_POST['user' . ucfirst($k)] = $v; }
                    $uid    = $CUsers->create(array('returning' => 'id'));
                    
                    // Insert him into the proper groups
                    $_POST      = array();
                    $ugp        = array( 'user_id' => $uid, 'group_id' => $groups['technicians']['id']);
                    foreach ($ugp as $k => $v) { $_POST['usersgroup' . ucfirst($k)] = $v; }
                    $CUsersgroups->create();
                    
                    $_POST      = array();
                    $ugp        = array( 'user_id' => $uid, 'group_id' => $groups['admins']['id']);
                    foreach ($ugp as $k => $v) { $_POST['usersgroup' . ucfirst($k)] = $v; }
                    $CUsersgroups->create();
                }
                
                $technid = $uid;
            }

            // Handle the team chief
            if ( !empty($tmpData[22]) )
            {
                /*
                // Try to find the user in the db
                $uid = $CUsers->retrieve(array(
                    'getFields'     => 'id',
                    'conditions'    => array('sector_code' => $tmpData[22]),
                    'limit'         => 1,
                ));
                
                // If the commercial user id has not beed found, create it
                if ( empty($uid) )
                {
                    // Create the user
                    $_POST      = array();
                    foreach ($u as $k => $v) { $_POST['user' . ucfirst($k)] = $v; }
                    $uid    = $CUsers->create(array('returning' => 'id'));
                    
                    // Insert him into the proper groups
                    $_POST      = array();
                    $ugp        = array( 'user_id' => $uid, 'group_id' => $groups['teamchiefs']['id']);
                    foreach ($ugp as $k => $v) { $_POST['usersgroup' . ucfirst($k)] = $v; }
                    $CUsersgroups->create();
                    
                    $_POST      = array();
                    $ugp        = array( 'user_id' => $uid, 'group_id' => $groups['admins']['id']);
                    foreach ($ugp as $k => $v) { $_POST['usersgroup' . ucfirst($k)] = $v; }
                    $CUsersgroups->create();
                }
                
                $teamChiefId = $uid;
                */
            }
            
            /*
            $client = array(
                'name'      => $tmpData[20],
            );
            */

            $machine = array(
                'code'                  => trim($tmpData[0]),
                'number'                => $tmpData[1],
                'position'              => (int) $tmpData[2],
                'model'                 => $tmpData[3],
                'place_code'            => (float) $tmpData[4],
                'install_date'          => !empty($tmpData[5]) ? DateTime::createFromFormat('d/m/Y H:i:s', $tmpData[5])->getTimestamp() : null,
                'uninstall_date'        => !empty($tmpData[6]) ? DateTime::createFromFormat('d/m/Y H:i:s', $tmpData[6])->getTimestamp() : null,
                'place_name'            => strtolower($tmpData[12]),
                'commercial_code'       => $tmpData[13],
                'sector_code'           => $tmpData[14],
                'technician_user_id'    => $technid,
                // [skip], [skip], [skip], [skip], [skip
                'client_code'           => $tmpData[15],
                'technical_area_code'   => $tmpData[18],
                'technical_area_name'   => $tmpData[19],
                //
                'model_name'            => $tmpData[21],
                'team_chief_code'       => $tmpData[22],
                // [skip],
                'company_code'          => $tmpData[24],
                'model_category_name'   => $tmpData[25],
                'client_category_name'  => $tmpData[26],
                'model_family'          => $tmpData[27],
                // [skip],
                'commercial_user_id'    => $ccialid,
                'department'            => strtolower($tmpData[30]),
                'city'                  => strtolower($tmpData[31]),
                // [skip], [skip], [skip],
                'accountant_code'       => $tmpData[34],
                'accountant_code'       => $tmpData[35],
                'contract_type'         => $tmpData[36],
                //'warranty_end_date'       => !empty($tmpData[37]) ? DateTime::createFromFormat('d/m/Y H:i:s', $tmpData[37])->getTimestamp() : null,
            );
            
            // Add the machine to the db
            $_POST = array();
            foreach ( $machine as $k => $v ){ $_POST['machine' . ucfirst($k)] = $v; }
            $CMachines->create();
            $result = $CMachines->success;

            // If a 4030 error (creation error due to unique key constraint) is returned
            // try to update instead
            if ( in_array(4030, $CMachines->errors) )
            {
                $CMachines->update(array('by' => 'code', 'values' => $machine['code']));
                $updateResult = $CMachines->success;
                
                $updated = $updateResult ? $updated++ : $updated;                
            }
            
//var_dump($machine);
            
//$this->logError('Machine ' . $rowNb . ' : ' . ($result ? 'OK' : 'ERROR'));
//$this->logError(!$result && !$updated ? 'Error on line ' . (string) ($rowNb) : '');
//$this->logError(!$result && !$updated ? 'Raw data:' . (string) ($row) : '');

if ( !$result && !$updated && !empty($CMachines->errors) )
{
//var_dump($tmpData);
var_dump($CMachines->errors);
}

//$this->logError(!$result && !$updated && isset($CMachines->model->launchedQuery) ? $CMachines->model->launchedQuery : '');
//$this->logError(!$result && !$updated ? '--------------------------------------' : '');

            if ( $result ){ $imported++; }

            unset($tmpData, $machine, $ccial, $techn, $updateResult);
            $rowNb++;
        }
        
        $t2     = microtime(true);
        $ptime  = ($t2 - $t1);
        
        // Update the task with the processed items number
        if ( ($imported + $updated) > 0 )
        {
            $task = array(
                'processed_items_nb'    => $imported + $updated,
            );
            $_POST = array();
            foreach ( $task as $k => $v ){ $_POST['task' . ucfirst($k)] = $v; }
            $CTasks->update(array('by' => 'id', 'values' => $tId));
            $_POST = null;
        }
        
        $succesLog = 'TOTAL: processed in: ' . $ptime . 's' . $this->lb;
        $succesLog .= 'TOTAL: ' . $rowNb . ' row(s) found' . $this->lb;
        $succesLog .= 'TOTAL: ' . $imported . ' record(s) inserted' . $this->lb;
        $succesLog .= 'TOTAL: ' . $updated . ' record(s) updated' . $this->lb;
        $succesLog .= 'BATCH END ----' . $this->lb;
        print_r($succesLog);
        
        return $this;
    }
    
    
    // If the passed error is an object or an array print_r it
    // otherwise, we just make a simple print
    public function logError($err = '', $options = array())
    {
        // Set a shortcut for options, extending default params by passed one
        $o = array_merge(array('autoEOL' => true), $options);
        
        // Do no continue if the error is an empty string
        if ( $err === '' ){ return $this; }
                
        //$log = is_string($err) ? $err : print_r($err);
        $log = (is_array($err) || is_object($err) ) ? print_r($err, true) : $err;
        //$log .= $o['autoEOL'] ? PHP_EOL : '';
        $log .= $o['autoEOL'] ? '<br/>' : '';
        
        /*
        // Open the error file
        $handle = fopen($this->errLogPath, 'a');
        
        // Write the log message into the file
        fwrite($handle, $log);
        
        // Release the file
        fclose($handle);
        */
        
        echo $log;
        
        $log    = null;
        
        //ob_start();
        
        return $this;
    }
    
}
?>