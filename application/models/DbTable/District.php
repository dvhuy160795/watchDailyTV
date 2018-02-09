<?php

class Application_Model_DbTable_District extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_district';

    public function getDistrictByConditionAnd (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('district_is_deleted = ?','0');
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

    public function getDistrictByConditionOR (&$aryUser = null , $condition = null) {
    	
    }

}
