<?php
/*
* Class to check authentication
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_System_Auth{

	private $_messages;
    
    public function login($arrParam =  null){
        $db = Zend_Registry::get('connectDB');
	   	$auth = Zend_Auth::getInstance();	
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
	   	$authAdapter = new Zend_Auth_Adapter_DbTable($db);
	    $authAdapter->setTableName('admin')
	    			->setIdentityColumn('user_name')
	    			->setCredentialColumn('password');
	    $uname = trim($arrParam['user_name']);
	    $password = trim($arrParam['password']);
		if ($uname == "" || $password == "") {
			return false;
		} else {
		    $paswd = sha1($password,false);
    		$authAdapter->setIdentity($uname);
    		$authAdapter->setCredential($paswd);
    		$select = $authAdapter->getDbSelect();	
			$result = $auth->authenticate($authAdapter);         
    		if ($result->isValid()) {
    			$omitColumns = array('password');
    			$data = $authAdapter->getResultRowObject(null,$omitColumns);
                
	            $session = new Zend_Session_Namespace('Zend_Auth_Admin');
                
                // Store admin info
                $auth->getStorage()->write($data);
                                
                // Set the time of user logged in
	            $session->setExpirationSeconds(24*3600);        
                // If "remember" was marked
	            if (!empty($arrParam['remember'])) {
	                Zend_Session::rememberMe();
	            }
                
                // Regenerate session to fight against session fixation
                session_regenerate_id();
                
    			return true;
    		} else {
    			return false;
    		}
        }
        
    }
    
    public function logout(){
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
        $auth->clearIdentity();
    }
}
