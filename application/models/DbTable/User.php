<?php

class Application_Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_user';

    public function insertNewUser ($aryUser, &$newIdUser, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryUser);
    		$newIdUser = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }

    public function updateUser () {

    }

    public function getOneUser (&$aryUser = null , $condition = null) {
    	$where = $this->_db->quoteInto('user_code = ?',12);
    	$where .= $this->_db->quoteInto(' and 1 = ?',1231);
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$arr = $this->_db->fetchRow($sql);
    	var_dump($arr);
    	echo $where;die;
    }

    public function getUserByMailAndMoreByOR (&$aryUser = null , $condition = null, $email) {
    	$where = $this->_db->quoteInto('user_email = ?',$email);
    	foreach ($condition as $key => $value) {
    		$where .= $this->_db->quoteInto(" or ".$key." = ?",$value);
    	}
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryUser = $this->_db->fetchRow($sql);
		if (empty($aryUser)) {
		 	return false;
		} else {
			return true;
		}
    }
    public function getMultiUser () {

    }
}

