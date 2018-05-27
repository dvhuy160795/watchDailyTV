<?php

class Application_Model_DbTable_Permission extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_permission';
    
    public function getPermissionByConditionAnd (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('video_is_deleted = ?','0');
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            $aryResult = [];
            return false;
        } else {
            return true;
        }
    }
    
    public function getMultiPermissionConditionLike(&$aryResult = null , $condition = null) {
        $where = $this->_db->quoteInto('video_is_deleted = ?','0');
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." LIKE ?","%".$value."%");
        }
        
        $sql = $this->_db->select()->from($this->_name,["id"])->where($where);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            $aryResult = [];
            return false;
        } else {
            return true;
        }
    }
}

