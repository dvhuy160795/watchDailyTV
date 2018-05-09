<?php

class Application_Model_DbTable_Video extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_video';
    
    public function insertNewVideo ($aryVideo, &$newIdVideo, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryVideo);
    		$newIdVideo = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }
    
    public function updateVideoByCode ($aryVideoUpdate = [],$condition = [], $code,&$err) {
        try {
            $where = "id = "."'".$code."'";
            if ($condition != []) {
                foreach ($condition as $key => $value) {
                    $where .= " AND ".$key." = ".$value;
                }
            }
            
            $intIsOk = $this->_db->update($this->_name,$aryVideoUpdate,$where);
            return $intIsOk;
        }
        catch (Zend_Db_Exception $e) {
            $err = $e->getMessage();
            return $intIsOk = -2;
        }
    }
    public function getVideoByMailAndMoreByOR (&$aryVideo = null , $condition = null) {
    	$where = $this->_db->quoteInto('video_is_deleted = ?',0);
    	foreach ($condition as $key => $value) {
    		$where .= $this->_db->quoteInto(" or ".$key." = ?",$value);
    	}
    	$sql = $this->_db->select()->from($this->_name)->where($where)->order("id DESC");
    	$aryVideo = $this->_db->fetchAll($sql);
        if (empty($aryVideo)) {
                return false;
        } else {
                return true;
        }
    }
    
    public function getOneVideoByMailAndMoreByAND (&$aryVideo = null , $condition = null) {
    	$where = $this->_db->quoteInto('video_is_deleted = ?',0);
    	foreach ($condition as $key => $value) {
    		$where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
    	}
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryVideo = $this->_db->fetchRow($sql);
        if (empty($aryVideo)) {
                return false;
        } else {
                return true;
        }
    }

    public function getVideoByMailAndMoreByAND (&$aryResult = null , $condition = null) {
        $where = $this->_db->quoteInto('video_is_deleted = ?',0);
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where)->order("id DESC");
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            $aryResult = [];
            return false;
        } else {
            return true;
        }
    }
}

