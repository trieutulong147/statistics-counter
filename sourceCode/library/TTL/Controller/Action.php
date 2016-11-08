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
        
    	$this->view->arrParam = $this->_arrParam;
        $this->view->controller =  $this->_request->getControllerName();
		$this->view->action =  $this->_request->getActionName();
		$this->view->module = $this->_request->getModuleName();
	}
	
	protected function _loadTemplate($template_path, $fileConfig = 'template.ini',$sectionConfig = 'template'){
		
        // Prepare variables
		$filename = $template_path . "/" . $fileConfig;
		$section = $sectionConfig;
		$config = new Zend_Config_Ini($filename,$section);
		$config = $config->toArray();
		
		$baseUrl = $this->_request->getBaseUrl();
		$templateUrl = $baseUrl .$config['url'];
		$cssUrl = $templateUrl . $config['dirCss'];
		$jsUrl = $templateUrl . $config['dirJs'];
		$imgUrl = $templateUrl . $config['dirImg'];
		
        // Set empty for title, meta tag, css file, js file 
        $this->__resetLayout();
        
		// Set title
		$this->view->headTitle($config['title']);
        
		$this->__appendMetaTag($config);
		$this->__appendCssFile($config);
        $this->__appendJsFile($config);
		
		
		$this->view->templateUrl = $templateUrl;
		$this->view->cssUrl = $cssUrl;
		$this->view->jsUrl = $jsUrl;
		$this->view->imgUrl = $imgUrl;
		
		$option = array('layoutPath'=> $template_path, 'layout'=> $config['layout']);
		Zend_Layout::startMvc($option);
		
	}
    
    private function __resetLayout() {
        $this->view->headTitle()->set('');
		$this->view->headMeta()->getContainer()->exchangeArray(array());
		$this->view->headLink()->getContainer()->exchangeArray(array());
		$this->view->headScript()->getContainer()->exchangeArray(array());
    }
    
    private function __appendMetaTag ($config) {
        if (array_key_exists('metaHttp', $config) && count($config['metaHttp']) > 0) {		
			foreach ($config['metaHttp'] as $key => $value) {
				$tmp = explode("|",$value);				
				$this->view->headMeta()->appendHttpEquiv($tmp[0],$tmp[1]);
			}
		}
		
		if (array_key_exists('metaName', $config) && count($config['metaName']) > 0) {		
			foreach ($config['metaName'] as $key => $value) {
				$tmp = explode("|",$value);				
				$this->view->headMeta()->appendName($tmp[0],$tmp[1]);
			}
		}
    }
    
    private function __appendCssFile ($config) {
        if (array_key_exists('fileCss', $config) && count($config['fileCss']) > 0 ) {		
			foreach ($config['fileCss'] as $key => $css) {
				$this->view->headLink()->appendStylesheet($cssUrl . $css,'screen');
			}
		}
    }
    
    private function __appendJsFile ($config) {
        if (array_key_exists('fileJs', $config) && count($config['fileJs']) > 0) {		
			foreach ($config['fileJs'] as $key => $js) {
				$this->view->headScript()->appendFile($jsUrl . $js,'text/javascript');
			}
		}
    }
    
    // Show data in json form
    protected function _showJson($data, $jsonEncode = 1){
        if ($jsonEncode == 1) {
            $data = json_encode($data);    
        }
        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody($data);
        return;
    }
}