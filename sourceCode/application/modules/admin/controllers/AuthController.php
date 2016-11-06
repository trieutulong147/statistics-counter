<?php

class Admin_AuthController extends TTL_Controller_Action
{	
    public function init() {        
        parent::init();
    }
    

    public function loginAction()
    {
    	$template_path = TEMPLATE_PATH . "/admin/";
		$this->_loadTemplate($template_path,'admin_template.ini','login');
        
        if ($this->_request->isPost()) {
            $auth = new TTL_System_Auth();
            
            if ($auth->login($this->_arrParam)) {
            	if( $uri_callback = $this->_request->getParam('uri_callback') ) {
            		$this->_redirect($uri_callback);
            	} else {
            		$this->_redirect("/admin/");
            	}
            } else {
            	$this->view->error = "Username or password is not correct!";
            }
        }
    }
    
    public function logoutAction()
    {
        $auth = new TTL_System_Auth();
        $auth->logout();
        $this->_redirect('/admin/auth/login');
    }
}
		