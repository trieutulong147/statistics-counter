<?php
class Admin_IndexController extends TTL_Controller_Action{
    protected $_adminInfo;
    
    public function init() {
         parent::init();
    }
    
    public function preDisPatch() {
		$auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
        
		// Set layout
		$templatePath = TEMPLATE_PATH . "/admin/";
		$this->_loadTemplate($templatePath, 'admin_template.ini', 'admin_template');
		
		$adminInfo = $auth->getIdentity();
		$this->_adminInfo = $adminInfo;
		$this->view->adminInfo = $adminInfo;
    }
    
    public function indexAction(){
    	
    }
    
    public function noPermissionAction() {
        $this->view->notice = 'You don\'t have permission to do this action!';
    }
}