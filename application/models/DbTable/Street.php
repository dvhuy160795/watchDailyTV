 <?php

class Application_Model_DbTable_Street extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_street';

    public function getStreetByConditionAnd (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('street_is_deleted = ?','0');
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            return false;
        } else {
            return true;
        }
    }
    public function getOneStreet (&$aryStreet = null , $condition = null,$streetCode = "") {
    	$where = $this->_db->quoteInto('street_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and '.$key.' = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryStreet = $this->_db->fetchRow($sql);
    }
    public function getStreetByConditionOR (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('street_is_deleted = ?','0');
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" OR ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            return false;
        } else {
            return true;
        }
    }

    public function getListCity (&$aryResult) {
        $sql = $this->_db->select()->from($this->_name);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            return false;
        } else {
            return true;
        }
    }
}
