<?php
class Model_Admin_DomainModel extends Zend_Db_Table_Abstract {
	protected $_name = 'domain';
    protected $_primary = 'id';
    
    /**
	 * Return array of domain info
	 * @param ArrayObject $params: Array of parameters
	 * @return ArrayObject $result: Array of domain info
	 */
    public function getInfo($params = null) {
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $mysql = $db->select()
                    ->from(array('d' => $this->_name));
        
        // If have name of domain, get only one row of that domain
        if (!empty($params['domain_name'])) {
            $mysql->where('d.name = ?', $params['domain_name']);
            
            $result = $db->fetchRow($mysql);
            return $result;
        }
                    
        $result = $db->fetchAll($mysql);
        return $result;
    }
}