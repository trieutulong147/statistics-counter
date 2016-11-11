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
        $this->_templatePath = TEMPLATE_PATH . "/admin/";
		$this->_fileConfig = 'admin_template.ini'; 
        $this->_sectionConfig = 'admin_template';
        parent::_loadTemplate();
		
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