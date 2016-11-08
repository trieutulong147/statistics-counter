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
	    $uname = trim($arrParam['user_name']);
	    $password = trim($arrParam['password']);
        $remember = !empty($arrParam['remember']) ? 1 : 0;
		if ($uname == "" || $password == "") {
			return false;
		} else {
		    $paswd = sha1($password,false);
            return $this->__checkIsLogin($uname, $paswd, $remember);            
        }
        
    }
    
    private function __checkIsLogin ($uname, $paswd, $remember) {
        $authAdapter = $this->__getAuthAdapter($uname, $paswd);
        $auth = Zend_Auth::getInstance();	
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
		$result = $auth->authenticate($authAdapter);         
		if ($result->isValid()) {
            $this->__handleWhenLogin($authAdapter, $auth, $remember);
			return true;
		} else {
			return false;
		}
    }
    
    private function __handleWhenLogin ($authAdapter, $auth, $remember) {
        $omitColumns = array('password');
		$data = $authAdapter->getResultRowObject(null,$omitColumns);
        
        $session = new Zend_Session_Namespace('Zend_Auth_Admin');
        
        // Store admin info
        $auth->getStorage()->write($data);
                        
        // Set the time of user logged in
        $session->setExpirationSeconds(24*3600);
                
        // If "remember" was marked
        if ($remember) {
            Zend_Session::rememberMe();
        }
        
        // Regenerate session to fight against session fixation
        session_regenerate_id();
    }
    
    private function __getAuthAdapter ($uname, $paswd) {
        $db = Zend_Registry::get('connectDB');
	   	$authAdapter = new Zend_Auth_Adapter_DbTable($db);
	    $authAdapter->setTableName('admin')
	    			->setIdentityColumn('user_name')
	    			->setCredentialColumn('password')
                    ->setIdentity($uname)
                    ->setCredential($paswd);
        return $authAdapter;
    }
    
    public function logout(){
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
        $auth->clearIdentity();
    }
}
