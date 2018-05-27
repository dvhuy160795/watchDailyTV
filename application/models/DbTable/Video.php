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
            if ($value !== "") {
                $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
            }
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
    
    public function getVideoByConditionAndOrder (&$aryResult = null , $condition = null, $order = null) {
        $where = $this->_db->quoteInto('video_is_deleted = ?',0);
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where)->order($order);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            $aryResult = [];
            return false;
        } else {
            return true;
        }
    }
    
    public function getVideoByConditionAndOrderLimit (&$aryResult = null , $condition = null, $order = null) {
        $where = $this->_db->quoteInto('video_is_deleted = ?',0);
        foreach ($condition as $key => $value) {
            if ($value !== "") {
                $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
            }
        }
        $sql = $this->_db->select()->from($this->_name)->where($where)->order($order)->limit(30,1);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            $aryResult = [];
            return false;
        } else {
            return true;
        }
    }
    
    public function getVideoNotInListByConditionAndOrderLimitInAddLIstVideo (&$aryResult = null , $condition = null, $order = null) {
        $where = $this->_db->quoteInto('video_is_deleted = ?',0);
        foreach ($condition as $key => $value) {
            if ($value !== "") {
                if ($key == "video_list_code") {
                    $where .= $this->_db->quoteInto(" AND ".$key." != ?",$value);
                    continue;
                }
                $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
            }
        }
        $sql = $this->_db->select()->from($this->_name)->where($where)->order($order)->limit(30,1);
        $aryResult = $this->_db->fetchAll($sql);
        if (empty($aryResult)) {
            $aryResult = [];
            return false;
        } else {
            return true;
        }
    }
    
    public function getVideoByWhere($where, $aryField = "*") {
        if ($where === "") {
            return [];
        }
        $sql = $this->_db->select()->from($this->_name)->where($where)->order("id DESC");
        $aryResult = $this->_db->fetchAll($sql);
        return $aryResult;
    }
    public function buildSqlConditionSearchVideo($param = [],$textSearch) {
        $where = $this->_db->quoteInto('video_is_deleted = ?',0);
        $where .= " And ( ".$this->_db->quoteInto("video_title LIKE ?","%".$textSearch."%");
        $where .= " OR ".$this->_db->quoteInto("video_description LIKE ?","%".$textSearch."%");
        if ($param['video_type_account'] && $param['video_type_account'] != []){
            
            $where .= " OR ".$this->_db->quoteInto("video_type_account IN (?)",$param['video_type_account']);
            if ($param['video_video_type_code'] && $param['video_video_type_code'] != []) {
                $where .= " OR ".$this->_db->quoteInto("video_video_type_code IN (?)",$param['video_video_type_code']);
            }
        }
        $where .= ")";
//        echo $where;die;
        return $where;
    }
    
}

