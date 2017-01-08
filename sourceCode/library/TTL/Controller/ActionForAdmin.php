<?php
/*
* Parent Class for all controller in admin module  
* Author: TTL
* Create time: 16/11/2016
* Update time: 16/11/2016
*/
class TTL_Controller_ActionForAdmin extends TTL_Controller_Action {
    protected $_adminInfo;
    
	public function preDisPatch() {
        $this->__setLayout();
		$this->__setAdminInfo();
    }
    
    private function __setLayout() {
        $this->_templatePath = TEMPLATE_PATH . "/admin/";
		$this->_fileConfig = 'admin_template.ini'; 
        $this->_sectionConfig = 'admin_template';
        parent::_loadTemplate();
    }
    
    private function __setAdminInfo() {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_Admin'));
        $adminInfo = $auth->getIdentity();
		$this->_adminInfo = $adminInfo;
		$this->view->adminInfo = $adminInfo;
    }
}