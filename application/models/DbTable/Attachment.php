<?php

class Application_Model_DbTable_Attachment extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_attachment';
    
    public function insertAttachment ($aryAttachment, &$newIdAttachment, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryAttachment);
    		$newIdAttachment = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }
    
    public function getOneAttachment (&$aryResult = null , $condition = null) {
    	$where = $this->_db->quoteInto('attachment_is_deleted = ?','0');
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
}

