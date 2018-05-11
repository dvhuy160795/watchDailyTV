<?php

class Application_Model_DbTable_Comment extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_comment';
    
    public function insertNewComment ($aryComment, &$newIdComment, &$err) {
    	try {
            $intIsOk = $this->_db->insert($this->_name,$aryComment);
            $newIdComment = $this->_db->lastInsertId();
            return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }
    public function getCommentByConditionAnd (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('comment_is_deleted = ?','0');
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

    public function getCommentByConditionOR (&$aryResult = null , $condition = null) {
    	
    }

}