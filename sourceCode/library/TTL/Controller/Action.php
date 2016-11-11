<?php
/*
* Parent Class for all controller in project  
* Author: TTL
* Create time: 06/11/2016
* Update time: 06/11/2016
*/
class TTL_Controller_Action extends Zend_Controller_Action{
	protected $_arrParam;
    protected $_ip;
    protected $_templatePath;
    protected $_fileConfig = 'template.ini';
    protected $_sectionConfig = 'template';
    protected $_config;
    
	public function init(){
    	$this->_arrParam = $this->_request->getParams();   
		
        // Unset unnecessary field
		unset($this->_arrParam['module']);
		unset($this->_arrParam['controller']);
		unset($this->_arrParam['action']);
		
        // Get Ip
        $this->_ip = TTL_Utilities_Ip::getIpAddress();
        
        // Filter variables
		foreach ($this->_arrParam as &$p){
			$p = TTL_Utilities_String::filterString($p);
		}
        
        // Transfer variables to view
    	$this->view->arrParam = $this->_arrParam;
        $this->view->controller =  $this->_request->getControllerName();
		$this->view->action =  $this->_request->getActionName();
		$this->view->module = $this->_request->getModuleName();
	}
	
	protected function _loadTemplate(){
        // Prepare variables
		$filename = $this->_templatePath . "/" . $this->_fileConfig;
		$section = $this->_sectionConfig;
		$config = new Zend_Config_Ini($filename, $section);
		$this->_config = $config->toArray();
		
		$baseUrl = $this->_request->getBaseUrl();
		$templateUrl = $baseUrl .$this->_config['url'];
		$cssUrl = $templateUrl . $this->_config['dirCss'];
		$jsUrl = $templateUrl . $this->_config['dirJs'];
		$imgUrl = $templateUrl . $this->_config['dirImg'];
		
        // Set empty for title, meta tag, css file, js file 
        $this->__resetLayout();
        
		// Set title
		$this->view->headTitle($this->_config['title']);
        
		$this->__appendMetaTag();
		$this->__appendCssFile();
        $this->__appendJsFile();
		
		
		$this->view->templateUrl = $templateUrl;
		$this->view->cssUrl = $cssUrl;
		$this->view->jsUrl = $jsUrl;
		$this->view->imgUrl = $imgUrl;
		
		$option = array('layoutPath' => $this->_templatePath, 'layout' => $this->_config['layout']);
		Zend_Layout::startMvc($option);
		
	}
    
    private function __resetLayout() {
        $this->view->headTitle()->set('');
		$this->view->headMeta()->getContainer()->exchangeArray(array());
		$this->view->headLink()->getContainer()->exchangeArray(array());
		$this->view->headScript()->getContainer()->exchangeArray(array());
    }
    
    private function __appendMetaTag () {
        $this->__appendHtmlElementToView('metaHttp');
        $this->__appendHtmlElementToView('metaName');
    }
    
    private function __appendCssFile () {
        $this->__appendHtmlElementToView('fileCss');
    }
    
    private function __appendJsFile () {
        $this->__appendHtmlElementToView('fileJs');
    }
    
    private function __appendHtmlElementToView ($element) {
        if (array_key_exists($element, $this->_config) && count($this->_config[$element]) > 0) {		
			foreach ($this->_config[$element] as $key => $value) {
				$tmp = explode("|",$value);				
				$this->view->headMeta()->appendHttpEquiv($tmp[0],$tmp[1]);
			}
		}
    }
}