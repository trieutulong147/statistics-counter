<?php
/*
* Class to check authentication
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_System_Auth {
    private $__authAdapter;
    private $__auth;
    private $__isRemember;
    private $__username;
    private $__password;
    
    public function login($arrParam =  null) {
	    $username = trim($arrParam['user_name']);
	    $password = trim($arrParam['password']);
        $isRemember = !empty($arrParam['remember']) ? 1 : 0;
        
		if ($username == "" || $password == "") {
			return false;
		} else {
            $this->__username = $username;
		    $this->__password = sha1($password,false);
            $this->__isRemember = $isRemember;
            return $this->__checkIsLogin();            
        }
    }
    
    private function __checkIsLogin() {
        $this->__setAuthAdapter();
        $this->__setAuth();
		
		if ($this->__auth->authenticate($this->__authAdapter)->isValid()) {
            $this->__handleWhenLogin();
			return true;
		} else {
			return false;
		}
    }
    
    private function __setAuthAdapter() {
        $db = Zend_Registry::get('connectDB');
	   	$authAdapter = new Zend_Auth_Adapter_DbTable($db);
	    $authAdapter->setTableName('admin')
	    			->setIdentityColumn('user_name')
	    			->setCredentialColumn('password')
                    ->setIdentity($this->__username)
                    ->setCredential($this->__password);
        $this->__authAdapter = $authAdapter;
    }
    
    private function __setAuth() {
        $auth = Zend_Auth::getInstance();	
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
        $this->__auth = $auth;
    }
    
    private function __handleWhenLogin() {
        // Store admin info
        $omitColumns = array('password');
		$data = $this->__authAdapter->getResultRowObject(null,$omitColumns);
        $this->__auth->getStorage()->write($data);
        
        $session = new Zend_Session_Namespace('Zend_Auth_Admin');
        $session->setExpirationSeconds(24*3600);
        
        if ($this->__isRemember) {
            Zend_Session::rememberMe();
        }
        
        // Regenerate session to fight against session fixation
        Zend_Session::regenerateId();
    }
    
    public function logout() {
        $this->__setAuth();
        $this->__auth->clearIdentity();
    }
}
