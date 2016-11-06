<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoLoad(){
        $autoloader = new Zend_Application_Module_Autoloader( array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH,
        ));
        
        return $autoloader;
    }
    
    protected function _initDb(){
        $dbOptionds = $this->getOption('resources');
        $dbOptionds = $dbOptionds['db'];
        
        // Set up db
        $db = Zend_Db::factory($dbOptionds['adapter'], $dbOptionds['params']);
        $db->setFetchMode(Zend_Db::FETCH_ASSOC);
        
        // Store DB info to connectDB        
        Zend_Registry::set('connectDB', $db);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        return $db;
    }
    
    protected function _initSetConstants(){
        $config = parse_ini_file(APPLICATION_PATH . '/configs/constant.ini');
        foreach($config as $key=>$value){
            if(!defined($key)){
                define($key, $value);
            }
        }
    }
    
    protected function _initFrontController(){
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new TTL_Plugin_Auth());
        $front->addModuleDirectory(APPLICATION_PATH . "/modules");
        return $front;
    }
}