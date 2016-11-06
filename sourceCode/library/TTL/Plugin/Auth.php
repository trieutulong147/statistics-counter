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
		
		$need_auth = TRUE;
		
		$free_access = array(
			"default" => array(),
			"admin" => array("auth", "error")
		);
		
		if ( in_array($moduleName, array_keys($free_access) ) ) {
			if ((count($free_access[$moduleName]) == 0) || in_array($controllerName, $free_access[$moduleName])) {
				$need_auth = FALSE;
			}
		}
		
		if($need_auth){
            $auth = Zend_Auth::getInstance();            
            if ($moduleName == "admin") {
                // Check login session
                $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
                if ($auth->hasIdentity()) {
                    
                } else {
                     // Check ajax request?
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header("HTTP/1.1 403 Forbidden");
                        exit();
                    } else {
                        // If there is no login session, go back to login page
                        $request->setParam('uri_callback', $request->getRequestUri());
                        $request->setModuleName('admin');
        				$request->setControllerName('auth');
        				$request->setActionName('login');
                    }
                }
            }
		}
	}
}