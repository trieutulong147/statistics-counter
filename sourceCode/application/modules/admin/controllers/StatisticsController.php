<?php

class Admin_StatisticsController extends TTL_Controller_Action{
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
    
	public function indexAction() {
        try {
            // Append css, js file
            $this->view->headLink()->appendStylesheet('/plugins/datatables/dataTables.bootstrap.css');
            $this->view->headScript()->appendFile('/plugins/datatables/jquery.dataTables.min.js');
            $this->view->headScript()->appendFile('/plugins/datatables/dataTables.bootstrap.min.js');
            
        } catch (Exception $e) {
            $this->view->error = $e->getMessage();
        } 
	} 
    
    public function getStatisticsAjaxAction() {
        // Remove view and layout
        $this->_helper->layout->disableLayout(true);
		$this->_helper->viewRenderer->setNoRender(true);
        try {            
            // Prepare parameters
    		$params = $this->_request->getParams(); // Get paramters without filtering
            $logRequestModel = new Model_Admin_LogRequestModel();
    		$dataResponse = array();
    		$totaldata = 0;
    		$recordsFiltered = 0;
    
    		$arrayStatistics = $logRequestModel->getStatistics();
            $x = 1;
    		foreach ($arrayStatistics as $value) {
    			$domainName = $value['domain_name'];
                $hits = $value['hits'];
                $users = $value['users'];
                
                $item = array($x, $domainName, $hits, $users);
    			array_push($dataResponse, $item);
                $x++;
    		}
    
    		$totaldata = sizeof($dataResponse);
    
    		$response = array(
                "data" => $dataResponse
            );
    	
            $this->_showJson($response);
        } catch (Exception $e) {
            echo $e->getMessages();
        }
    }
}