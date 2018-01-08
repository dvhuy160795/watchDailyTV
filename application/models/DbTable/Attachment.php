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

}

