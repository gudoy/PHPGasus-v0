<?php

class VUnittests extends View
{
	var $noSession = true;
	
	public function __construct()
	{
		//$this->resourceName 	= strtolower(preg_replace('/^V(.*)/','$1', __CLASS__));
		//$this->resourceSingular = 'sample'; // use only if: singular !== (resourceName - "s") 
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct();
		
		return $this;
	}
	
	public function index()
	{
		$this->testConditions();
		
		//return $this->render();
	}
	
	
	public function testConditions()
	{
		$CUsers 		= new CUsers();
		$CSessions 		= new CSessions();
		
		// Expected user 'anonymous'
		$test1 			= $CUsers->index(array('conditions' => array('id' => '1')));
		$pass1 			= !empty($test1) && count($test1) === 1  && !empty($test1[0]['email']) && $test1[0]['email'] === 'nobody@anonymous.com';
		
		// Expected user 'anonymous'
		$test2 			= $CUsers->index(array('conditions' => array('id' => 1)));
		$pass2 			= !empty($test2) && count($test2) === 1 && !empty($test2[0]['email']) && $test2[0]['email'] === 'nobody@anonymous.com';
		
		// Expected user 'anonymous'
		$test3 			= $CUsers->index(array('conditions' => array(array('id','=','1'))));
		$pass3 			= !empty($test3) && count($test3) === 1 && !empty($test3[0]['email']) && $test3[0]['email'] === 'nobody@anonymous.com';
		
		// Expected user 'anonymous'
		$test4 			= $CUsers->index(array('conditions' => array(array('id','=',1))));
		$pass4 			= !empty($test4) && count($test4) === 1 && !empty($test4[0]['email']) && $test4[0]['email'] === 'nobody@anonymous.com';
		
		// Expected user 'anonymous'
		$test5 			= $CUsers->index(array('conditions' => array(array('id','1'))));
		$pass5 			= !empty($test5) && count($test5) === 1 && !empty($test5[0]['email']) && $test5[0]['email'] === 'nobody@anonymous.com';
		
		// Expected user 'anonymous'
		$test6 			= $CUsers->index(array('conditions' => array(array('id',1))));
		$pass6 			= !empty($test6) && count($test6) === 1 && !empty($test6[0]['email']) && $test6[0]['email'] === 'nobody@anonymous.com';
		
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test7 			= $CUsers->index(array('conditions' => array('id' => '1,2')));
		$pass7 			= !empty($test7) && count($test7) === 2 && !empty($test7[0]['email']) && $test7[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test7[1]['email']) && $test7[1]['email'] === 'guyllaume@clicmobile.com';
		
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test8 			= $CUsers->index(array('conditions' => array('id' => array(1,2))));
		$pass8 			= !empty($test8) && count($test8) === 2 && !empty($test8[0]['email']) && $test8[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test8[1]['email']) && $test8[1]['email'] === 'guyllaume@clicmobile.com';
							
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test9 			= $CUsers->index(array('conditions' => array(array('id','=','1,2'))));
		$pass9 			= !empty($test9) && count($test9) === 2 && !empty($test9[0]['email']) && $test9[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test9[1]['email']) && $test9[1]['email'] === 'guyllaume@clicmobile.com';
							
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test10 		= $CUsers->index(array('conditions' => array(array('id','=',array(1,2)))));
		$pass10			= !empty($test10) && count($test10) === 2 && !empty($test10[0]['email']) && $test10[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test10[1]['email']) && $test10[1]['email'] === 'guyllaume@clicmobile.com';

		// Expected user 'rlacroix@photomaton.com'
		$test11 		= $CUsers->index(array('conditions' => array('id,auth_level_nb' => '10')));
		$pass11 		= !empty($test11) && count($test11) === 1 && !empty($test11[0]['email']) && $test11[0]['email'] === 'rlacroix@photomaton.com';
		
		$test12 		= $CUsers->index(array('conditions' => array('id,auth_level_nb' => 10)));
		$pass12 		= !empty($test12) && count($test12) === 1 && !empty($test12[0]['email']) && $test12[0]['email'] === 'rlacroix@photomaton.com';
		
		$test13 		= $CUsers->index(array('conditions' => array(array('id','<','2'))));
		$pass13 		= !empty($test13) && count($test13) === 1 && !empty($test13[0]['email']) && $test13[0]['email'] === 'nobody@anonymous.com';
		
		$test14 		= $CUsers->index(array('conditions' => array(array('id','<',2))));
		$pass14 		= !empty($test14) && count($test14) === 1 && !empty($test14[0]['email']) && $test14[0]['email'] === 'nobody@anonymous.com';
		
		$test15 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>','1'))));
		$pass15 		= !empty($test15) && count($test15) === 1 && !empty($test15[0]['email']) && $test15[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test16 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1))));
		$pass16 		= !empty($test16) && count($test16) === 1 && !empty($test16[0]['email']) && $test16[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test17 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), 'id' => '2')));
		$pass17 		= !empty($test17) && count($test17) === 1 && !empty($test17[0]['email']) && $test17[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test18 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), 'id' => 2)));
		$pass18 		= !empty($test18) && count($test18) === 1 && !empty($test18[0]['email']) && $test18[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test19 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','=','2'))));
		$pass19 		= !empty($test19) && count($test19) === 1 && !empty($test19[0]['email']) && $test19[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test20 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','=',2))));
		$pass20 		= !empty($test20) && count($test20) === 1 && !empty($test20[0]['email']) && $test20[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test21 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','2'))));
		$pass21 		= !empty($test21) && count($test21) === 1 && !empty($test21[0]['email']) && $test21[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test22 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id',2))));
		$pass22 		= !empty($test22) && count($test22) === 1 && !empty($test22[0]['email']) && $test22[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test23 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','1,2'))));
		$pass23 		= !empty($test23) && count($test23) === 1 && !empty($test23[0]['email']) && $test23[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test24 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id',array('1','2')))));
		$pass24 		= !empty($test24) && count($test24) === 1 && !empty($test24[0]['email']) && $test24[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test25 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id',array(1,2)))));
		$pass25 		= !empty($test25) && count($test25) === 1 && !empty($test25[0]['email']) && $test25[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test26 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','=','1,2'))));
		$pass26 		= !empty($test26) && count($test26) === 1 && !empty($test26[0]['email']) && $test26[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test27 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','=',array('1','2')))));
		$pass27 		= !empty($test27) && count($test27) === 1 && !empty($test27[0]['email']) && $test27[0]['email'] === 'guyllaume@clicmobile.com';
		
		$test28 		= $CUsers->index(array('limit' => 1, 'conditions' => array(array('id','>',1), array('id','=',array(1,2)))));
		$pass28 		= !empty($test28) && count($test28) === 1 && !empty($test28[0]['email']) && $test28[0]['email'] === 'guyllaume@clicmobile.com';
		
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test29 			= $CUsers->index(array('conditions' => array(array('id','in','1,2'))));
		$pass29 			= !empty($test29) && count($test29) === 2 && !empty($test29[0]['email']) && $test29[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test29[1]['email']) && $test29[1]['email'] === 'guyllaume@clicmobile.com';
							
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test30 			= $CUsers->index(array('conditions' => array(array('id','in',array('1','2')))));
		$pass30 			= !empty($test30) && count($test30) === 2 && !empty($test30[0]['email']) && $test30[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test30[1]['email']) && $test30[1]['email'] === 'guyllaume@clicmobile.com';
							
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test31 			= $CUsers->index(array('conditions' => array(array('id','in',array(1,2)))));
		$pass31 			= !empty($test31) && count($test30) === 2 && !empty($test31[0]['email']) && $test31[0]['email'] === 'nobody@anonymous.com' 
							&& !empty($test31[1]['email']) && $test31[1]['email'] === 'guyllaume@clicmobile.com';
							
//var_dump($test31);
							
							
		// Expected users 'anonymous' & 'guyllaume@clicmobile.com'
		$test32 			= $CUsers->index(array('conditions' => array('creation_date' => strtotime('2010-09-28 14:20:52'))));
		$pass32 			= !empty($test32) && count($test32) === 1 && !empty($test32[0]['email']) && $test32[0]['email'] === 'nobody@anonymous.com';
		
		$test33 			= $CUsers->index(array('conditions' => array('creation_date' => '1285683652')));
		$pass33 			= !empty($test33) && count($test33) === 1 && !empty($test33[0]['email']) && $test33[0]['email'] === 'nobody@anonymous.com';
		
		$test34 			= $CUsers->index(array('conditions' => array('creation_date' => 1285683652)));
		$pass34 			= !empty($test34) && count($test34) === 1 && !empty($test34[0]['email']) && $test34[0]['email'] === 'nobody@anonymous.com';
		
		/*
		// Expected session (should use proper alias for email field in users table)
		//$test6 = $CSessions->index(array('user_email' => 'guyllaume@clicmobile.com')));
		*/
		
		$limit = 34;
		for ($i=1; $i<=$limit; $i++)
		{
			//$test = 'test' . $i;
			//var_dump($$test);
			
			$pass = 'pass' . $i;
			echo ($i < 10 ? '0' : '') . $i . ': ' . '<span class="' . ($$pass ? 'valid green">PASS' : 'error red">FAIL') . '</span><br/>';
		}
		
		return $this;
	}
};

?>