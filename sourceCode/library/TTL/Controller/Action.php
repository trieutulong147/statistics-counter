<?php
/*
* Parent Class for all controller in project  
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Controller_Action extends Zend_Controller_Action {
	protected $_arrParam;
    protected $_ip;
    protected $_templatePath;
    protected $_fileConfig = 'template.ini';
    protected $_sectionConfig = 'template';
    protected $_config;
    protected $_templateUrl = '';
    protected $_cssUrl = '';
    protected $_jsUrl = '';
    protected $_imgUrl = '';
    
	public function init() {
        $this->__getIp();
        
        $this->__filterInput();
        
    	$this->__transferVariablesIntoView();
	}
    
    private function __getIp() {
        $this->_ip = TTL_Utilities_Ip::getIpAddress();
    }
    
    private function __filterInput() {
        $this->_arrParam = $this->_request->getParams();   
		
        // Unset unnecessary field
		unset($this->_arrParam['module']);
		unset($this->_arrParam['controller']);
		unset($this->_arrParam['action']);
        
        // Trim and strip tags
		foreach ($this->_arrParam as &$p) {
			$p = TTL_Utilities_String::filterString($p);
		}
    }
    
    private function __transferVariablesIntoView() {
        $this->view->arrParam = $this->_arrParam;
        $this->view->controller =  $this->_request->getControllerName();
		$this->view->action =  $this->_request->getActionName();
		$this->view->module = $this->_request->getModuleName();
    }
	
	protected function _loadTemplate() {
        $this->__parseConfigInfo();
		
        // Set empty for title, meta tag, css file, js file 
        $this->__resetLayout();
        
        $this->__setTitle();
        
		$this->__appendMetaTag();
		$this->__appendCssFile();
        $this->__appendJsFile();
		
		$this->__transferFilesUrlIntoView();
		$this->__startMvcLayout();
	}
    
    private function __parseConfigInfo() {
		$filename = $this->_templatePath . "/" . $this->_fileConfig;
		$section = $this->_sectionConfig;
		$config = new Zend_Config_Ini($filename, $section);
		$baseUrl = $this->_request->getBaseUrl();
        
        $this->_config = $config->toArray();
		$this->_templateUrl = $baseUrl . $this->_config['url'];
		$this->_cssUrl = $this->_templateUrl . $this->_config['dirCss'];
		$this->_jsUrl = $this->_templateUrl . $this->_config['dirJs'];
		$this->_imgUrl = $this->_templateUrl . $this->_config['dirImg'];
    }
    
    private function __resetLayout() {
        $this->view->headTitle()->set('');
		$this->view->headMeta()->getContainer()->exchangeArray(array());
		$this->view->headLink()->getContainer()->exchangeArray(array());
		$this->view->headScript()->getContainer()->exchangeArray(array());
    }
    
    private function __setTitle() {
        $this->view->headTitle($this->_config['title']);
    }
    
    private function __appendMetaTag() {
        $this->__appendHtmlElementToView('metaHttp');
        $this->__appendHtmlElementToView('metaName');
    }
    
    private function __appendCssFile() {
        $this->__appendHtmlElementToView('fileCss');
    }
    
    private function __appendJsFile() {
        $this->__appendHtmlElementToView('fileJs');
    }
    
    private function __appendHtmlElementToView($element) {
        if (array_key_exists($element, $this->_config) && count($this->_config[$element]) > 0) {		
			foreach ($this->_config[$element] as $key => $value) {
				$tmp = explode("|",$value);				
				$this->view->headMeta()->appendHttpEquiv($tmp[0],$tmp[1]);
			}
		}
    }
    
    private function __transferFilesUrlIntoView() {
        $this->view->templateUrl = $this->_templateUrl;
		$this->view->cssUrl = $this->_cssUrl;
		$this->view->jsUrl = $this->_jsUrl;
		$this->view->imgUrl = $this->_imgUrl;
    }
    
    private function __startMvcLayout() {
        $option = array('layoutPath' => $this->_templatePath, 'layout' => $this->_config['layout']);
		Zend_Layout::startMvc($option);
    }
}