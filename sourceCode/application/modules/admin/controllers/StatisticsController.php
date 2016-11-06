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
		$template_path = TEMPLATE_PATH . "/admin/";
		$this->_loadTemplate($template_path, 'admin_template.ini', 'admin_template');
		
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
            $log_request_model = new Model_Admin_LogRequestModel();
    		$data_response = array();
    		$totaldata = 0;
    		$recordsFiltered = 0;
    
    		$array_statistics = $log_request_model->getStatistics();
            $x = 1;
    		foreach ($array_statistics as $value) {
    			$domain_name = $value['domain_name'];
                $hits = $value['hits'];
                $users = $value['users'];
                
                $item = array($x, $domain_name, $hits, $users);
    			array_push($data_response, $item);
                $x++;
    		}
    
    		$totaldata = sizeof($data_response);
    
    		$response = array(
                "data" => $data_response
            );
    	
            $this->_showJson($response);
        } catch (Exception $e) {
            echo $e->getMessages();
        }
    }
}