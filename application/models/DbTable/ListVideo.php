<?php

class Application_Model_DbTable_ListVideo extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_video_group';
    
    public function insertNewListVideo ($aryGroupVideo, &$newIdGroupVideo, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryGroupVideo);
    		$newIdGroupVideo = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }
    
    public function getGroupVideoByConditionAnd (&$aryResult = null , $condition = null) {
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
    
    public function getMultiListVideoConditionLike(&$aryResult = null , $condition = null) {
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

