<?php
class IndexController extends TTL_Controller_ActionForDefault {
    public function indexAction() {

    }
    
    public function beaconAction() {
        try {
            /* -------------------------------- Prepare parameters ------------------------------- */
            
            $domainName = TTL_Utilities_Domain::getDomainOfRequest();
            $requestTime = date('Y-m-d H:i:s');
            $logRequestModel = new Model_Admin_LogRequestModel();
            $domainModel = new Model_Admin_DomainModel();
            $dataToInsertLog = array(
                'ip' => $this->_ip,
                'request_time' => $requestTime
            );
            $currentNumberHits = 0;
            $imagePath = '/images/tmp/hit.jpeg';
            
            /* ------------------------------------ Process ------------------------------------- */
            
            if ($domainName) {
                // Check domain if exist
                $domainInfo = $domainModel->getInfo(array('domain_name' => $domainName));
                
                if (!empty($domainInfo)) {
                    $domainId = $domainInfo['id'];
                    $domainModel->update(array('number_request' => new Zend_Db_Expr('number_request + 1')), 'id = ' . $domainId);
                    
                    $currentNumberHits = $domainInfo['number_request'] + 1;
                } else {
                    $domainId = $domainModel->insert(array(
                        'name' => $domainName,
                        'number_request' => 1
                    ));
                    
                    $currentNumberHits = 1;
                }
                
                // Insert Data to Log
                $dataToInsertLog['domain_id'] = $domainId;
                $logRequestModel->insert($dataToInsertLog);
                $imagePath = '/images/tmp/' . $domainId . uniqid(time()) . '_hit.jpeg';
            }
        } catch (Exception $e) {
            // Do nothing
        }
        
        // Remove view
        $this->_helper->viewRenderer->setNoRender();
        
        $this->__showImageOfCurrentNumberHits($imagePath, $currentNumberHits);
    }    
    
    private function __showImageOfCurrentNumberHits($imagePath, $currentNumberHits) {
        header('Content-Type: image/jpeg');
        $img = TTL_Utilities_Image::createJpegImageWithString($imagePath, number_format($currentNumberHits));
        imagejpeg($img);
        imagedestroy($img);
    }
}