<?php
class Admin_StatisticsController extends TTL_Controller_ActionForAdmin {
    
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
            $response = $this->__createStatisticsData();
            $this->_helper->json($response);
        } catch (Exception $e) {
            echo $e->getMessages();
        }
    }
    
    private function __createStatisticsData() {
        // Prepare parameters
		$params = $this->_request->getParams(); // Get paramters without filtering
        $logRequestModel = new Model_Admin_LogRequestModel();
		$dataResponse = array();

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

		$response = array(
            "data" => $dataResponse
        );
        
        return $response;
    }
}