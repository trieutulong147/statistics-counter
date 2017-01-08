<?php
/*
* Parent Class for all controller in default module  
* Author: TTL
* Create time: 16/11/2016
* Update time: 16/11/2016
*/
class TTL_Controller_ActionForDefault extends TTL_Controller_Action {
    protected $_adminInfo;
    
	public function preDisPatch() {
        $this->__setLayout();
    }
    
    private function __setLayout() {
        $this->_templatePath = TEMPLATE_PATH . "/default/";
		$this->_fileConfig = 'template.ini'; 
        $this->_sectionConfig = 'template';
        parent::_loadTemplate();
    }
}