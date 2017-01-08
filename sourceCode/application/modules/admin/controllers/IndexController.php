<?php
class Admin_IndexController extends TTL_Controller_ActionForAdmin {
    
    public function indexAction() {
    	
    }
    
    public function noPermissionAction() {
        $this->view->notice = 'You don\'t have permission to do this action!';
    }
}