<?php

class Application_Model_DbTable_Comment extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_comment';
    
    public function insertNewComment ($aryComment, &$newIdComment, &$err) {
        var_dump($aryComment);die;
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
    public function getCountryByConditionAnd (&$aryResult = null , $condition = null) {
    	
    }

    public function getCountryByConditionOR (&$aryResult = null , $condition = null) {
    	
    }

}