<?php
class Model_Admin_LogRequestModel extends Zend_Db_Table_Abstract {
	protected $_name = 'log_request';
    protected $_primary = 'id';
    
    /**
	 * Return array of statistics
	 * @param ArrayObject $params: Array of parameters
	 * @return ArrayObject $result: array of statistics
	 */
	public function getStatistics ($params=null) {
		$db = Zend_Db_Table::getDefaultAdapter();
        
		$mySql = $db->select()
					->from(array('l_r' => $this->_name), array('hits' => 'COUNT(l_r.id)', 'users' => 'COUNT(DISTINCT l_r.ip)'))
                    ->join(array('d' => 'domain'), 'd.id = l_r.domain_id', array('domain_name' => 'd.name'))
                    ->group('d.id');
        
        $result = $db->fetchAll($mySql);
        return $result;
	}
}