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

    public function updateUserByCode ($aryUserUpdate = [],$condition = [], $code,&$err) {
        try {
            $where = "user_code = "."'".$code."'";
            if ($condition != []) {
                foreach ($condition as $key => $value) {
                    $where .= " AND ".$key." = ".$value;
                }
            }
            
            $intIsOk = $this->_db->update($this->_name,$aryUserUpdate,$where);
            return $intIsOk;
        }
        catch (Zend_Db_Exception $e) {
            $err = $e->getMessage();
            return $intIsOk = -2;
        }
    }

    public function getOneUser (&$aryUser = null , $condition = null,$userCode = "") {
    	$where = $this->_db->quoteInto('user_code = ?',$userCode);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and $key = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryUser = $this->_db->fetchRow($sql);
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

    public function getUserByMailAndMoreByAND (&$aryUser = null , $condition = null, $email) {
        $where = $this->_db->quoteInto('user_email = ?',$email);
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
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

    public function getUserByConditionByAnd ($condition, &$aryUser) {
        $where = "user_is_deleted = 0";
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryUser = $this->_db->fetchRow($sql);
        if (empty($aryUser)) {
            return false;
        } else {
            return true;
        }
    }
}

