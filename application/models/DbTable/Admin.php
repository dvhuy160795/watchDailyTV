<?php

class Application_Model_DbTable_Admin extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_admin';

    public function insertNewAdmin ($aryAdmin, &$newIdAdmin, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryAdmin);
    		$newIdUser = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }

    public function updateAdminByCode ($aryAdminUpdate = [],$condition = [], $code,&$err) {
        try {
            $where = "id = "."'".$code."'";
            if ($condition != []) {
                foreach ($condition as $key => $value) {
                    $where .= " AND ".$key." = ".$value;
                }
            }
            
            $intIsOk = $this->_db->update($this->_name,$aryAdminUpdate,$where);
            return $intIsOk;
        }
        catch (Zend_Db_Exception $e) {
            $err = $e->getMessage();
            return $intIsOk = -2;
        }
    }

    public function getOneAdmin (&$aryAdmin = null , $condition = null,$adminCode = "") {
    	$where = $this->_db->quoteInto('admin_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and '.$key.' = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryAdmin = $this->_db->fetchRow($sql);
    }
    
    public function getOneAdminByIsDelete (&$aryUser = null , $condition = null) {
    	$where = $this->_db->quoteInto('admin_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and '.$key.' = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryUser = $this->_db->fetchRow($sql);
        if (empty($aryUser)) {
		 	return false;
		} else {
			return true;
		}
    }
    public function getAdminAndMoreByOR (&$aryUser = null , $condition = null, $email) {
    	$where = $this->_db->quoteInto('admin_is_deleted = ?',0);
    	foreach ($condition as $key => $value) {
    		$where .= $this->_db->quoteInto(" or ".$key." = ?",$value);
    	}
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryUser = $this->_db->fetchAll($sql);
		if (empty($aryUser)) {
		 	return false;
		} else {
			return true;
		}
    }

    public function getAdminAndMoreByAND (&$aryUser = null , $condition = null, $email) {
        $where = $this->_db->quoteInto('admin_is_deleted = ?',0);
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryUser = $this->_db->fetchAll($sql);
        if (empty($aryUser)) {
            return false;
        } else {
            return true;
        }
    }

    public function getMultiAdmin (&$aryUser = null , $condition = null) {
        $where = $this->_db->quoteInto('user_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and '.$key.' = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryUser = $this->_db->fetchAll($sql);
    }
    public function getMultiAdminConditionLike(&$aryUser = null , $condition = null) {
        $where = $this->_db->quoteInto('user_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' AND '.$key.' LIKE ?',"%".$value."%");
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name,['user_code'])->where($where);
    	$aryUser = $this->_db->fetchAll($sql);
    }
    public function getAdminByConditionByAnd ($condition, &$aryUser) {
        $where = "user_is_deleted = 0";
        if ($condition != []) {
            foreach ($condition as $key => $value) {
                $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
            }   
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryUser = $this->_db->fetchAll($sql);
        if (empty($aryUser)) {
            return false;
        } else {
            return true;
        }
    }
}

