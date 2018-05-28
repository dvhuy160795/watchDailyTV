<?php

class Application_Model_DbTable_Grouppermission extends Zend_Db_Table_Abstract
{

    protected $_name = 'wtv_group_permission';

    public function insertNewGrouppermission ($aryGrouppermission, &$newIdGrouppermission, &$err) {
    	try {
    		$intIsOk = $this->_db->insert($this->_name,$aryGrouppermission);
    		$newIdUser = $this->_db->lastInsertId();
    		return $intIsOk;
    	}
    	catch (Zend_Db_Exception $e) {
    		$err = $e->getMessage();
    		return $intIsOk = -2;
    	}
    }

    public function updateGrouppermissionByCode ($aryAdminUpdate = [],$condition = [], $code,&$err) {
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

    public function getOneGrouppermission (&$aryAdmin = null , $condition = null) {
    	$where = $this->_db->quoteInto('group_permission_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and '.$key.' = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryAdmin = $this->_db->fetchRow($sql);
    }
    
    public function getOneGrouppermissionByIsDelete (&$aryUser = null , $condition = null) {
    	$where = $this->_db->quoteInto('group_permission_is_deleted = ?',0);
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
    public function getGrouppermissionAndMoreByOR (&$aryUser = null , $condition = null) {
    	$where = $this->_db->quoteInto('group_permission_is_deleted = ?',0);
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

    public function getGrouppermissionAndMoreByAND (&$aryGroups = null , $condition = null) {
        $where = $this->_db->quoteInto('group_permission_is_deleted = ?',0);
        foreach ($condition as $key => $value) {
            $where .= $this->_db->quoteInto(" AND ".$key." = ?",$value);
        }
        
        $sql = $this->_db->select()->from($this->_name)->where($where);
        $aryGroups = $this->_db->fetchAll($sql);
        if (empty($aryGroups)) {
            return false;
        } else {
            return true;
        }
    }

    public function getMultiGrouppermission (&$aryUser = null , $condition = null) {
        $where = $this->_db->quoteInto('group_permission_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' and '.$key.' = ?',$value);
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name)->where($where);
    	$aryUser = $this->_db->fetchAll($sql);
    }
    public function getMultiGrouppermissionConditionLike(&$aryUser = null , $condition = null) {
        $where = $this->_db->quoteInto('group_permission_is_deleted = ?',0);
        if ($condition != null) {
            foreach ($condition as $key => $value) {
               $where .= $this->_db->quoteInto(' AND '.$key.' LIKE ?',"%".$value."%");
            }
        }
    	
    	$sql = $this->_db->select()->from($this->_name,['user_code'])->where($where);
    	$aryUser = $this->_db->fetchAll($sql);
    }
    public function getGrouppermissionByConditionByAnd ($condition, &$aryUser) {
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

