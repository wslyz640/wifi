<?php
class BaseApiAction extends BaseAction{
	public  $browser=null;
	public $agent=null;
 	public function __construct() {
        parent::__construct();
        $this->browser=getUserBrowser();
        $this->agent=getAgent();
    }

	
}