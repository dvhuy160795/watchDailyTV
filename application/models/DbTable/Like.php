<?php

class Application_Model_DbTable_Like extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_like';
    
    public function insertLike ($aryLike, &$newIdLike, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryLike);
    		$newIdGroupVideo = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }
    
    public function updateLike ($aryUpdate = [],$condition = [], $code,&$err) {
        try {
            $where = "id = "."'".$code."'";
            if ($condition != []) {
                foreach ($condition as $key => $value) {
                    $where .= " AND ".$key." = ".$value;
                }
            }
            $intIsOk = $this->_db->update($this->_name,$aryUpdate,$where);
            return $intIsOk;
        }
        catch (Zend_Db_Exception $e) {
            $err = $e->getMessage();
            return $intIsOk = -2;
        }
    }
    
    public function getLikeByConditionAnd (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('like_is_deleted = ?','0');
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
    
    public function getMultiLikeConditionLike(&$aryResult = null , $condition = null) {
        $where = $this->_db->quoteInto('like_is_deleted = ?','0');
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

