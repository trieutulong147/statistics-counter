<?php
class IndexController extends TTL_Controller_Action {
    public function init() {
        parent::init();
    }

    public function predisPatch() {
        // Set layout
        $template_path = TEMPLATE_PATH . "/default/";
        $this->_loadTemplate($template_path, 'template.ini', 'template');
    }
    
    public function indexAction() {
        
    }
    
    public function beaconAction(){
        try {
            /* -------------------------------- Prepare parameters ------------------------------- */
            
            $ip = $this->_ip;
            $domain_name = TTL_Utilities_Domain::getDomainOfRequest();
            $request_time = date('Y-m-d H:i:s');
            $log_request_model = new Model_Admin_LogRequestModel();
            $domain_model = new Model_Admin_DomainModel();
            $data_to_insert_log = array(
                'ip'            => $ip,
                'request_time'  => $request_time
            );
            $current_number_hits = 0;
            $image_path = '/images/tmp/hit.jpeg';
            
            /* ------------------------------------ Process ------------------------------------- */
            
            if ($domain_name) {
                // Check domain if exist
                $domain_info = $domain_model->getInfo(array('domain_name' => $domain_name));
                
                if (!empty($domain_info)) {
                    $domain_id = $domain_info['id'];
                    $domain_model->update(array('number_request' => new Zend_Db_Expr('number_request + 1')), 'id = ' . $domain_id);
                    
                    $current_number_hits = $domain_info['number_request'] + 1;
                } else {
                    $domain_id = $domain_model->insert(array(
                        'name'              => $domain_name,
                        'number_request'    => 1
                    ));
                    
                    $current_number_hits = 1;
                }
                
                // Insert Data to Log
                $data_to_insert_log['domain_id'] = $domain_id;
                $log_request_model->insert($data_to_insert_log);
                $image_path = '/images/tmp/' . $domain_id . uniqid(time()) . '_hit.jpeg';
            }
        } catch (Exception $e) {
            // Do nothing
        }
        
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        header('Content-Type: image/jpeg');
        $img = TTL_Utilities_Image::createJpegImageWithString($image_path, number_format($current_number_hits));
        imagejpeg($img);
        imagedestroy($img);
    }
}
