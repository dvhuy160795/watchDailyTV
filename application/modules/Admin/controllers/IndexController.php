<?php

class Admin_IndexController extends Zend_Controller_Action
{

    protected $dbAdmin;
    protected $dbPermission;
    protected $dbGroupPermission;
    
    public function init()
    {
        $this->dbAdmin = new Application_Model_DbTable_Admin();
        $this->dbPermission = new Application_Model_DbTable_Permission();
        $this->dbGroupPermission = new Application_Model_DbTable_Grouppermission();
    }

    public function indexAction()
    {
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();
        if (!$_SESSION['Admin']) {
            $this->redirect('Admin/Index/login');
        }
    }

    public function loginAction()
    {
        $this->_helper->layout->disableLayout();
        
    }
    
    public function checkloginAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $condition = $params['admin'];
        $message = "";
        $this->dbAdmin->getOneAdmin($aryAdmin, $condition);
        if ($aryAdmin == NULL) {
            $intIsOk = 0;
            $message = "Login name or password is wrong!";
        } else {
            $_SESSION['Admin'] = $aryAdmin;
            $intIsOk = 1;
        }
        $arrReponse = [
            "message" => $message,
            "intIsOk" => $intIsOk
        ];
        echo json_encode($arrReponse);
    }
    
    public function logoutadminAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        unset($_SESSION['Admin']); 
        $arrReponse['intIsOk'] = 1;
        echo json_encode($arrReponse);
    }
    
    public function addadminAction() {
        $this->_helper->layout->disableLayout();
        $conditionGroup = [];
        $aryGroup = [];
        $this->dbGroupPermission->getMultiGrouppermission($aryGroup, $conditionGroup);
        $this->view->aryGroup = $aryGroup;
    }
    
    public function saveadminAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        if ($params['idAdmin'] == "") {
            $aryAdmin = [
                'admin_name' => $params['admin']['admin_name'],
                'admin_login_name' => $params['admin']['admin_login_name'],
                'admin_pass' => $params['admin']['admin_pass'],
                'admin_group_permisstion' => $params['admin']['admin_group_permisstion'],
                'update' => date("Y/m/d"),
                'created' => date("Y/m/d"),
                'admin_is_deleted' => 0,
            ];
            $intIsOk = $this->dbAdmin->insertNewAdmin($aryAdmin, $newIdAdmin, $err);
            $idAdmin = $newIdAdmin;
            if ($intIsOk != 1) { 
                var_dump($err);die;
            } else {
                $message = " Create admin success !!";
            }
        } else {
            if (isset($params['admin']['admin_name'])) {
                $aryAdmin['admin_name'] = $params['admin']['admin_name'];
            }
            if (isset($params['admin']['admin_login_name'])) {
                $aryAdmin['admin_login_name'] = $params['admin']['admin_login_name'];
            }
            if (isset($params['admin']['admin_pass'])) {
                $aryAdmin['admin_pass'] = $params['admin']['admin_pass'];
            }
            if (isset($params['admin']['admin_group_permisstion'])) {
                $aryAdmin['admin_group_permisstion'] = $params['admin']['admin_group_permisstion'];
            }
            $aryAdmin['update'] = date("Y/m/d");
            $intIsOk = $this->dbAdmin->updateAdminByCode($aryAdmin, $conditionUpdate = [],$params['idAdmin'], $err);
            $idAdmin = $params['idAdmin'];
            if ($intIsOk != 1) { 
                var_dump($err);die;
            } else {
                $message = " Edit admin success !!";
            }
        }
        
        $arrReponse = [
            "message" => $message,
            "intIsOk" => $intIsOk,
            "idAdmin" => $idAdmin
        ];
        echo json_encode($arrReponse);
    }
    
    public function loadlistmemberAction() {
        $this->_helper->layout->disableLayout();
        $aryAdmins = [];
        $this->dbAdmin->getMultiAdmin($aryAdmins, $conditionAdmin = null);
        $this->view->aryAdmins = $aryAdmins;
    }
    
    public function formaddgrouppermissionAction() {
        $this->_helper->layout->disableLayout();
        $aryPermissions = [];
        $this->dbPermission->getPermission($aryPermissions);
        $this->view->aryPermissions =  $aryPermissions;
    }
    
    public function savegrouppermissionAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        if ($params['idGroup'] == "") {
            $listPermission = "";
            foreach ($params['group']['group_permission_list_permission_code'] as $value) {
                $listPermission .= $value."|";
            }
            $aryGrouppermission = [
                'group_permission_name' => $params['group']['group_permission_name'],
                'group_permission_list_permission_code' => $listPermission,
                'group_permission_is_deleted' => 0,
                'updated' => date("Y/m/d"),
                'created' => date("Y/m/d"),
                'group_permission_creater' => $_SESSION['Admin']['id'],
            ];
            $this->dbGroupPermission->insertNewGrouppermission($aryGrouppermission, $newIdGrouppermission, $err);
            $message = " Create group success !!";
        } else {
            $listPermission = "";
            foreach ($params['group']['group_permission_list_permission_code'] as $value) {
                $listPermission .= $value."|";
            }
            $aryGrouppermissionUpdate = [
                'group_permission_name' => $params['group']['group_permission_name'],
                'group_permission_list_permission_code' => $listPermission,
                'updated' => date("Y/m/d"),
                'group_permission_update' => $_SESSION['Admin']['id'],
            ];
            $this->dbGroupPermission->updateGrouppermissionByCode($aryGrouppermissionUpdate, $conditionUpdate = [],$params['idGroup'], $err);
            $message = " Edit group success !!";
        }
        
        $arrReponse = [
            "message" => $message,
            "intIsOk" => 1
        ];
        echo json_encode($arrReponse);
    }
    
    public function loadlistgrouppermissionAction() {
        $this->_helper->layout->disableLayout();
        $aryGroups = [];
        $this->dbGroupPermission->getGrouppermissionAndMoreByAND($aryGroups, $conditionGroup = []);
        $aryAdmins = [];
        $this->dbAdmin->getMultiAdmin($aryAdmins, $conditionAdmin = null);
        $this->view->aryGroups = $aryGroups;
        $this->view->aryAdmins = $aryAdmins;
    }
    
    public function loadlistgrouppermissiononlylistgroupAction() {
        $this->_helper->layout->disableLayout();
        $aryGroups = [];
        $this->dbGroupPermission->getGrouppermissionAndMoreByAND($aryGroups, $conditionGroup = []);
        $aryAdmins = [];
        $this->dbAdmin->getMultiAdmin($aryAdmins, $conditionAdmin = null);
        $this->view->aryGroups = $aryGroups;
        $this->view->aryAdmins = $aryAdmins;
    }
    
    public function loadgroupdetailAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $aryGrouppermission = [];
        $conditionGrouppermission = [
            'id' => $params['idGroup']
        ];
        $this->dbGroupPermission->getOneGrouppermission($aryGrouppermission, $conditionGrouppermission);
        $this->view->aryGrouppermission = $aryGrouppermission;
        $aryPermissions = [];
        $this->dbPermission->getPermission($aryPermissions);
        $this->view->aryPermissions =  $aryPermissions;
        
        $aryAdmins = [];
        $conditionAdmin = [
            'admin_group_permisstion' => $params['idGroup']
        ];
        $this->dbAdmin->getAdminAndMoreByAND($aryAdmins, $conditionAdmin);
        $this->view->aryAdmins =  $aryAdmins;
        $htmlListmember = $this->view->render("index/listmemberbygroup.phtml");
        $htmlGroupdetail = $this->view->render("index/loadgroupdetail.phtml");
        $arrReponse = [
            "listMember" => $htmlListmember,
            "groupDetail" => $htmlGroupdetail
        ];
        echo json_encode($arrReponse);
    }
    
    public function loadadmindetailAction() {
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();
        $conditionGroup = [];
        $aryGroup = [];
        $this->dbGroupPermission->getMultiGrouppermission($aryGroup, $conditionGroup);
        $this->view->aryGroup = $aryGroup;
        $aryAdmins = [];
        $conditionAdmin = ['id' => $params['idAdmin']];
        $this->dbAdmin->getOneAdmin($aryAdmins, $conditionAdmin, $params['idAdmin']);
        $this->view->aryAdmins =  $aryAdmins;
    }
}

