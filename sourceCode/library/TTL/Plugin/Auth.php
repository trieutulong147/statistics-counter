<?php
/*
* Plugin for authentication
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$moduleName = $request->getModuleName();
		$controllerName = $request->getControllerName();
		
		if ($this->__checkNeedAuth($moduleName, $controllerName)) {
            $this->__authen($request, $moduleName);
		}
	}
    
    private function __checkNeedAuth ($moduleName, $controllerName) {
        $needAuth = TRUE;
		
		$freeAccess = array(
			"default" => array(),
			"admin" => array("auth", "error")
		);
		
		if (in_array($moduleName, array_keys($freeAccess))) {
			if ((count($freeAccess[$moduleName]) == 0) || in_array($controllerName, $freeAccess[$moduleName])) {
				$needAuth = FALSE;
			}
		}
        
        return $needAuth;
    }
    
    private function __authen ($request, $moduleName) {
        $auth = Zend_Auth::getInstance();            
        if ($moduleName == "admin") {
            // Check login session
            $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
            if ($auth->hasIdentity()) {
                // Do something
            } else {
                $this->__handleNotLogin($request);
            }
        }
    }
    
    private function __handleNotLogin ($request) {
        // Check ajax request?
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header("HTTP/1.1 403 Forbidden");
            exit();
        } else {
            // If there is no login session, go back to login page
            $this->__setLoginPage($request);
        }
    }
    
    private function __setLoginPage ($request) {
        $request->setParam('uri_callback', $request->getRequestUri());
        $request->setModuleName('admin');
		$request->setControllerName('auth');
		$request->setActionName('login');
    }
}