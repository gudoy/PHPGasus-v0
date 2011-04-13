<?php

class VUnittests extends View
{
	var $noSession = true;
	
    public function __construct(&$application)
    {
        parent::__construct($application);
        
        $this->options['debug'] = true;
		
		return $this;
	}
	
	public function index()
	{
	    if ( !$this->isInDebugMod() ) { $this->redirect(_URL_HOME); }
        
		//$this->testConditions();
		
		//return $this->render();
	}
    
    public function requests()
    {
        $CUsers         = new CUsers();
        
        // count
        $test1 = $CUsers->index(array('mode' => 'count', 'conditions' => array('id' => 1)));
        $pass1 = !empty($test1) && $test1 === 1;
            
        // 1 col, 1 row, (type primarykey)
        $test2 = $CUsers->retrieve(array('getFields' => 'id', 'conditions' => array('id' => 1)));
        $pass2 = $test2 === 1;
        
        // 1 col, 1 row, (type email => string)
        $test3 = $CUsers->retrieve(array('getFields' => 'email', 'conditions' => array('id' => 1)));
        $pass3 = $test3 === 'nobody@anonymous.com';
        
        // several cols, 1 row
        $test4 = $CUsers->retrieve(array('getFields' => 'id,email', 'conditions' => array('id' => 1)));
        $pass4 = is_array($test4) && count($test4) === 2 && $test4['id'] === 1 && $test4['email'] === 'nobody@anonymous.com';
        
        // several cols, 1 row
        $test5 = $CUsers->retrieve(array('conditions' => array('id' => 1)));
        $pass5 = is_array($test5) && $test5['id'] === 1 && $test5['email'] === 'nobody@anonymous.com';
		
//var_dump($test5);
        
        // 1 col, several rows
        $test6 = $CUsers->index(array('getFields' => 'email', 'conditions' => array('id' => '1,2')));
        $pass6 = is_array($test6) && count($test6) === 2 && $test6[0] === 'nobody@anonymous.com' && $test6[1] === 'guyllaume@clicmobile.com';
        
        // several cols, several rows
        $test7 = $CUsers->index(array('conditions' => array('id' => '1,2')));
        $pass7 = is_array($test7) && count($test7) === 2 && $test7[0]['email'] === 'nobody@anonymous.com' && $test7[1]['email'] === 'guyllaume@clicmobile.com';

        // 1 col, several rows, indexed by id
        $test8 = $CUsers->index(array('getFields' => 'id', 'conditions' => array('id' => 1), 'indexBy' => 'id'));
        $pass8 = $test8 === 1;
        
        
//var_dump(__FUNCTION__);
$nb = 8;
$testname = 'test' . $nb;
$passname = 'pass' . $nb;
var_dump($$testname);
var_dump($$passname);
        
        //$limit = 0;
        $limit = 7;
        for ($i=1; $i<=$limit; $i++)
        {
            $pass = 'pass' . $i;
            echo ($i < 10 ? '0' : '') . $i . ': ' . '<span class="' . ($$pass ? 'valid green">PASS' : 'error red">FAIL') . '</span><br/>';
        }
    }
	
	
	public function testConditions()
	{
		$CUsers 		= new CUsers();
		$CSessions 		= new CSessions();
		
        # Tests basic syntaxes
		$test1 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array('id' => '1')));
		$pass1 			= !empty($test1) && count($test1) === 1 && !empty($test1[0]['email']) && $test1[0]['email'] === 'nobody@anonymous.com';
		
		$test2 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array('id' => 1)));
		$pass2 			= !empty($test2) && count($test2) === 1 && !empty($test2[0]['email']) && $test2[0]['email'] === 'nobody@anonymous.com';
		
		$test3 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','=','1'))));
		$pass3 			= !empty($test3) && count($test3) === 1 && !empty($test3[0]['email']) && $test3[0]['email'] === 'nobody@anonymous.com';
		
		$test4 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','=',1))));
		$pass4 			= !empty($test4) && count($test4) === 1 && !empty($test4[0]['email']) && $test4[0]['email'] === 'nobody@anonymous.com';
		
		$test5 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','1'))));
		$pass5 			= !empty($test5) && count($test5) === 1 && !empty($test5[0]['email']) && $test5[0]['email'] === 'nobody@anonymous.com';
		
		$test6 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id',1))));
		$pass6 			= !empty($test6) && count($test6) === 1 && !empty($test6[0]['email']) && $test6[0]['email'] === 'nobody@anonymous.com';
		
		$test7 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array('id' => '1,2')));
		$pass7 			= !empty($test7) && count($test7) === 2 && !empty($test7[0]['email']) && $test7[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test7[1]['email']) && $test7[1]['email'] === 'guyllaume@clicmobile.com';
		
		$test8 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array('id' => array(1,2))));
		$pass8 			= !empty($test8) && count($test8) === 2 && !empty($test8[0]['email']) && $test8[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test8[1]['email']) && $test8[1]['email'] === 'guyllaume@clicmobile.com';
							
		$test9 			= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','=','1,2'))));
		$pass9 			= !empty($test9) && count($test9) === 2 && !empty($test9[0]['email']) && $test9[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test9[1]['email']) && $test9[1]['email'] === 'guyllaume@clicmobile.com';
							
		$test10 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','=',array(1,2)))));
		$pass10			= !empty($test10) && count($test10) === 2 && !empty($test10[0]['email']) && $test10[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test10[1]['email']) && $test10[1]['email'] === 'guyllaume@clicmobile.com';

// TODO: change with proper data (that will allways be in the default database)
// auth_level_nb column to be removed from the db since it's deprecated
$test11 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array('id,auth_level_nb' => '1')));
$pass11 		= !empty($test11) && count($test11) === 1 && !empty($test11[0]['email']) && $test11[0]['email'] === 'nobody@anonymous.com';

// TODO: change with proper data (that will allways be in the default database)
// auth_level_nb column to be removed from the db since it's deprecated
$test12 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array('id,auth_level_nb' => 1)));
$pass12 		= !empty($test12) && count($test12) === 1 && !empty($test12[0]['email']) && $test12[0]['email'] === 'nobody@anonymous.com';
		
		$test13 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','<','2'))));
		$pass13 		= !empty($test13) && count($test13) === 1 && !empty($test13[0]['email']) && $test13[0]['email'] === 'nobody@anonymous.com';
		
		$test14 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','<',2))));
		$pass14 		= !empty($test14) && count($test14) === 1 && !empty($test14[0]['email']) && $test14[0]['email'] === 'nobody@anonymous.com';
		
		$test15 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>','1'))));
		$pass15 		= !empty($test15) && count($test15) === 1 && !empty($test15[0]['email']) && $test15[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test16 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1))));
		$pass16 		= !empty($test16) && count($test16) === 1 && !empty($test16[0]['email']) && $test16[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test17 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), 'id' => '2')));
		$pass17 		= !empty($test17) && count($test17) === 1 && !empty($test17[0]['email']) && $test17[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test18 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), 'id' => 2)));
		$pass18 		= !empty($test18) && count($test18) === 1 && !empty($test18[0]['email']) && $test18[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test19 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','=','2'))));
		$pass19 		= !empty($test19) && count($test19) === 1 && !empty($test19[0]['email']) && $test19[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test20 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','=',2))));
		$pass20 		= !empty($test20) && count($test20) === 1 && !empty($test20[0]['email']) && $test20[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test21 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','2'))));
		$pass21 		= !empty($test21) && count($test21) === 1 && !empty($test21[0]['email']) && $test21[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test22 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id',2))));
		$pass22 		= !empty($test22) && count($test22) === 1 && !empty($test22[0]['email']) && $test22[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test23 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','1,2'))));
		$pass23 		= !empty($test23) && count($test23) === 1 && !empty($test23[0]['email']) && $test23[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test24 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id',array('1','2')))));
		$pass24 		= !empty($test24) && count($test24) === 1 && !empty($test24[0]['email']) && $test24[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test25 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id',array(1,2)))));
		$pass25 		= !empty($test25) && count($test25) === 1 && !empty($test25[0]['email']) && $test25[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test26 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','=','1,2'))));
		$pass26 		= !empty($test26) && count($test26) === 1 && !empty($test26[0]['email']) && $test26[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test27 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','=',array('1','2')))));
		$pass27 		= !empty($test27) && count($test27) === 1 && !empty($test27[0]['email']) && $test27[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test28 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1), array('id','=',array(1,2)))));
		$pass28 		= !empty($test28) && count($test28) === 1 && !empty($test28[0]['email']) && $test28[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test29 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','in','1,2'))));
		$pass29 		= !empty($test29) && count($test29) === 2 && !empty($test29[0]['email']) && $test29[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test29[1]['email']) && $test29[1]['email'] === 'guyllaume@clicmobile.com';

		$test30 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','in',array('1','2')))));
		$pass30 		= !empty($test30) && count($test30) === 2 && !empty($test30[0]['email']) && $test30[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test30[1]['email']) && $test30[1]['email'] === 'guyllaume@clicmobile.com';
							
		$test31 		= $CUsers->index(array('getFields' => 'id, email', 'conditions' => array(array('id','in',array(1,2)))));
		$pass31 		= !empty($test31) && count($test30) === 2 && !empty($test31[0]['email']) && $test31[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test31[1]['email']) && $test31[1]['email'] === 'guyllaume@clicmobile.com';

// TODO: change with proper data (that will allways be in the default database)							
# Tests timestamps
$test32 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('creation_date' => strtotime('2010-09-28 14:20:52'))));
$pass32 		= !empty($test32) && count($test32) === 1 && !empty($test32[0]['email']) && $test32[0]['email'] === 'nobody@anonymous.com';

$test33 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('creation_date' => '1285683652')));
$pass33 		= !empty($test33) && count($test33) === 1 && !empty($test33[0]['email']) && $test33[0]['email'] === 'nobody@anonymous.com';

$test34 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('creation_date' => 1285683652)));
$pass34 		= !empty($test34) && count($test34) === 1 && !empty($test34[0]['email']) && $test34[0]['email'] === 'nobody@anonymous.com';
    
    
		# Tests null
		$test35 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('name' => null)));
		$pass35 		= !empty($test35) && count($test35) === 1 && !empty($test35[0]['email']) && $test35[0]['email'] === 'nobody@anonymous.com';

		$test36 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('name' => 'null')));
		$pass36 		= !empty($test36) && count($test36) === 1 && !empty($test36[0]['email']) && $test36[0]['email'] === 'nobody@anonymous.com';
    
        $test37         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('name' => array(null))));
        $pass37         = !empty($test37) && count($test37) === 1 && !empty($test37[0]['email']) && $test37[0]['email'] === 'nobody@anonymous.com';
    
        $test38         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array('name' => array('null'))));
        $pass38         = !empty($test38) && count($test38) === 1 && !empty($test38[0]['email']) && $test38[0]['email'] === 'nobody@anonymous.com';
		
		$test39 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','=',null))));
		$pass39 		= !empty($test39) && count($test39) === 1 && !empty($test39[0]['email']) && $test39[0]['email'] === 'nobody@anonymous.com';

		$test40 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','in',null))));
		$pass40 		= !empty($test40) && count($test40) === 1 && !empty($test40[0]['email']) && $test40[0]['email'] === 'nobody@anonymous.com';
		
		$test41 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','is',null))));
		$pass41 		= !empty($test41) && count($test41) === 1 && !empty($test41[0]['email']) && $test41[0]['email'] === 'nobody@anonymous.com';
		
		$test42 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','equal',null))));
		$pass42 		= !empty($test42) && count($test42) === 1 && !empty($test42[0]['email']) && $test42[0]['email'] === 'nobody@anonymous.com';
		
		$test43 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','=','null'))));
		$pass43 		= !empty($test43) && count($test43) === 1 && !empty($test43[0]['email']) && $test43[0]['email'] === 'nobody@anonymous.com';
		
		$test44 		= $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','in','null'))));
		$pass44 		= !empty($test44) && count($test44) === 1 && !empty($test44[0]['email']) && $test44[0]['email'] === 'nobody@anonymous.com';
    
        $test45         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','is','null'))));
        $pass45         = !empty($test45) && count($test45) === 1 && !empty($test45[0]['email']) && $test45[0]['email'] === 'nobody@anonymous.com';
        
        $test46         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','equal','null'))));
        $pass46         = !empty($test46) && count($test46) === 1 && !empty($test46[0]['email']) && $test46[0]['email'] === 'nobody@anonymous.com';
		
    
        # Tests not null
        $test47         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','!=',null))));
        $pass47         = !empty($test47) && count($test47) === 1 && !empty($test47[0]['email']) && $test47[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test48         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','not in',null))));
        $pass48         = !empty($test48) && count($test48) === 1 && !empty($test48[0]['email']) && $test48[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test49         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','is not',null))));
        $pass49         = !empty($test49) && count($test49) === 1 && !empty($test49[0]['email']) && $test49[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test50         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','not equal',null))));
        $pass50         = !empty($test50) && count($test50) === 1 && !empty($test50[0]['email']) && $test50[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test51         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','!=','null'))));
        $pass51         = !empty($test51) && count($test51) === 1 && !empty($test51[0]['email']) && $test51[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test52         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','not in','null'))));
        $pass52         = !empty($test52) && count($test52) === 1 && !empty($test52[0]['email']) && $test52[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test53         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','is not','null'))));
        $pass53         = !empty($test53) && count($test53) === 1 && !empty($test53[0]['email']) && $test53[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test54         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('name','not equal','null'))));
        $pass54         = !empty($test54) && count($test54) === 1 && !empty($test54[0]['email']) && $test54[0]['email'] === 'guyllaume@clicmobile.com';
        
        # Tests >, >=, < , <=, greater, greater or equal, lower, lower or equal
        $test55         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>',1))));
        $pass55         = !empty($test55) && count($test55) === 1 && !empty($test55[0]['email']) && $test55[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test56         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>','1'))));
        $pass56         = !empty($test56) && count($test56) === 1 && !empty($test56[0]['email']) && $test56[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test57         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>=',2))));
        $pass57         = !empty($test57) && count($test57) === 1 && !empty($test57[0]['email']) && $test57[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test58         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','>=','2'))));
        $pass58         = !empty($test58) && count($test58) === 1 && !empty($test58[0]['email']) && $test58[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test59         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','<',3))));
        $pass59         = !empty($test59) && count($test59) === 1 && !empty($test59[0]['email']) && $test59[0]['email'] === 'nobody@anonymous.com';
        
        $test60         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','<','3'))));
        $pass60         = !empty($test60) && count($test60) === 1 && !empty($test60[0]['email']) && $test60[0]['email'] === 'nobody@anonymous.com';
        
        $test61         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','<=',2))));
        $pass61         = !empty($test61) && count($test61) === 1 && !empty($test61[0]['email']) && $test61[0]['email'] === 'nobody@anonymous.com';
        
        $test62         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','<=','2'))));
        $pass62         = !empty($test62) && count($test62) === 1 && !empty($test62[0]['email']) && $test62[0]['email'] === 'nobody@anonymous.com';
        
        $test63         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','greater',1))));
        $pass63         = !empty($test63) && count($test63) === 1 && !empty($test63[0]['email']) && $test63[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test64         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','greater','1'))));
        $pass64         = !empty($test64) && count($test64) === 1 && !empty($test64[0]['email']) && $test64[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test65         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','greater or equal',2))));
        $pass65         = !empty($test65) && count($test65) === 1 && !empty($test65[0]['email']) && $test65[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test66         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','greater or equal','2'))));
        $pass66         = !empty($test66) && count($test66) === 1 && !empty($test66[0]['email']) && $test66[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test67         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','lower',3))));
        $pass67         = !empty($test67) && count($test67) === 1 && !empty($test67[0]['email']) && $test67[0]['email'] === 'nobody@anonymous.com';
        
        $test68         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','lower','3'))));
        $pass68         = !empty($test68) && count($test68) === 1 && !empty($test68[0]['email']) && $test68[0]['email'] === 'nobody@anonymous.com';
        
        $test69         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','lower or equal',2))));
        $pass69         = !empty($test69) && count($test69) === 1 && !empty($test69[0]['email']) && $test69[0]['email'] === 'nobody@anonymous.com';
        
        $test70         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('id','lower or equal','2'))));
        $pass70         = !empty($test70) && count($test70) === 1 && !empty($test70[0]['email']) && $test70[0]['email'] === 'nobody@anonymous.com';

// TODO: change with proper data (that will allways be in the default database)
# Tests like, contains, not like, does not contains 
$test71         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('last_name','like','doe'))));
$pass71         = !empty($test71) && count($test71) === 1 && !empty($test71[0]['email']) && $test71[0]['email'] === 'nobody@anonymous.com';

$test72         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('last_name','contains','doe'))));
$pass72         = !empty($test72) && count($test72) === 1 && !empty($test72[0]['email']) && $test72[0]['email'] === 'nobody@anonymous.com';

$test73         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('last_name','not like','doe'))));
$pass73         = !empty($test73) && count($test73) === 1 && !empty($test73[0]['email']) && $test73[0]['email'] === 'guyllaume@clicmobile.com';

$test74         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('last_name','does not contains','doe'))));
$pass74         = !empty($test74) && count($test74) === 1 && !empty($test74[0]['email']) && $test74[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test75         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('email','starts by','nobody@'))));
        $pass75         = !empty($test75) && count($test75) === 1 && !empty($test75[0]['email']) && $test75[0]['email'] === 'nobody@anonymous.com';

        $test76         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('email','ends by','@anonymous.com'))));
        $pass76         = !empty($test76) && count($test76) === 1 && !empty($test76[0]['email']) && $test76[0]['email'] === 'nobody@anonymous.com';

        $test77         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('email','does not starts by','nobody@'))));
        $pass77         = !empty($test77) && count($test77) === 1 && !empty($test77[0]['email']) && $test77[0]['email'] === 'guyllaume@clicmobile.com';
        
        $test78         = $CUsers->index(array('getFields' => 'id, email', 'limit' => 1, 'conditions' => array(array('email','does not ends by','anonymous.com'))));
        $pass78         = !empty($test78) && count($test78) === 1 && !empty($test78[0]['email']) && $test78[0]['email'] === 'guyllaume@clicmobile.com';
        
//var_dump($pass78);
//$this->dump($CUsers->model->launchedQuery);
        
        # Tests booleans
        
        # Tests errors: unknow operator, unknow column, wrong values
        
        # Tests not in, 
        
        # Tests between
        
        # Tests multiple conditions
        
		/*
		// Expected session (should use proper alias for email field in users table)
		//$test6 = $CSessions->index(array('user_email' => 'guyllaume@clicmobile.com')));
		*/
		
		$limit = 78;
		for ($i=1; $i<=$limit; $i++)
		{
			$pass = 'pass' . $i;
			echo ($i < 10 ? '0' : '') . $i . ': ' . '<span class="' . ($$pass ? 'valid green">PASS' : 'error red">FAIL') . '</span><br/>';
		}
		
		return $this;
	}
};

?>