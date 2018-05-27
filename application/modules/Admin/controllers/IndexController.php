<?php

class Admin_IndexController extends Zend_Controller_Action
{

    protected $dbAdmin;
    
    public function init()
    {
        $this->dbAdmin = new Application_Model_DbTable_Admin();
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
    }
    
    public function formaddgrouppermissionAction() {
        $this->_helper->layout->disableLayout();
    }
}

