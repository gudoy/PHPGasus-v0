<?php

class VDebug extends View
{
    public function __construct(&$application)
    {
        parent::__construct($application);
		
		$this->options['debug'] = true;
		
		return $this;
	}
	
	
	public function index()
	{
		if ( !$this->isInDebugMod() ) { $this->redirect(_URL_HOME); }
		
		// TODO: <ul> of all available functions
		
		//return $this->render();
	}
	
	public function ua()
	{
	    return $this->userAgent();
	}
    
    public function userAgent()
    {
        if ( !$this->isInDebugMod() ) { $this->redirect(_URL_HOME); }
        
        echo $_SERVER['HTTP_USER_AGENT'];   
    }
	
	public function browser()
	{
		if ( !$this->isInDebugMod() ) { $this->redirect(_URL_HOME); }
		
		if ( !_APP_SNIFF_BROWSER ) { die('Ooops, Browser sniffing is disabled!'); }
		
		var_dump($this->browser);
	}
	
	public function platform()
	{
		if ( !$this->isInDebugMod() ) { $this->redirect(_URL_HOME); }
		
		if ( !_APP_SNIFF_PLATFORM ) { die('Ooops, Platform sniffing is disabled!'); }
		
		var_dump($this->platform);
	}
	
    
	public function info()
	{
		if ( !$this->isInDebugMod() ) { $this->redirect(_URL_HOME); }
		
		phpinfo();
	}
    
    public function phpinfo()
    {
        return $this->info();
    }
	
};

?>