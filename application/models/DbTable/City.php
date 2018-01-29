<?php

class Application_Model_DbTable_City extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_city';

    public function getCityByConditionAnd (&$aryUser = null , $condition = null) {
    	$where = $this->_db->quoteInto('city_is_deleted = ?','0');
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryUser = $this->_db->fetchRow($sql);
        if (empty($aryUser)) {
            return false;
        } else {
            return true;
        }
    }

    public function getCityByConditionOR (&$aryUser = null , $condition = null) {
    	$where = $this->_db->quoteInto('city_is_deleted = ?','0');
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" OR ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryUser = $this->_db->fetchRow($sql);
        if (empty($aryUser)) {
            return false;
        } else {
            return true;
        }
    }

    public function getListCity (&$aryResult) {
        $sql = $this->_db->select()->from($this->_name);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryUser)) {
            return false;
        } else {
            return true;
        }
    }
}
