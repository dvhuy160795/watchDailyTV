<?php

class Application_Model_DbTable_VideoType extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_video_type';
    
    public function insertNewVideoType ($aryVideo, &$newIdVideo, &$err) {
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
    
    public function getVideoTypeByConditionAnd (&$aryResult = null , $condition = null) {
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
}
