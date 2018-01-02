<?php

class UserController extends Zend_Controller_Action
{
    private $_message = "";
    private $_intIsOk = 1;

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//        $this->_helper->layout()->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function registerAction() {
        $this->_helper->layout->disableLayout();
        $this->view->res = $this->_request->getParams();
    }

    public function loginAction() {
        $this->_helper->layout->disableLayout();
    }

    public function checkexistuserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $file = new Zend_File_Transfer();
        $logic = new Application_Model_Logic();

        $params = $this->_request->getParams();
        $fileInfo = $file->getFileInfo();
        
        $arrItemError = [];
        $arrTypeFileCondition = [
            "image/png",
            "image/gif",
            "image/jpg",
            "image/jpeg"
        ];
        if (!empty($fileInfo)) {
            if (!in_array($fileInfo['avatarUser']['type'],$arrTypeFileCondition)){
                $this->_message .= "type image alias invalid!"."<br>";
            }
            
            if ($fileInfo['avatarUser']['size'] > 2000) {
                $this->_message .= "size image alias need <= 2000kb!"."<br>";
            }
        }
        
        if (!preg_match("/[a-zA-Z]/", $params['user']['user_first_name'])) {
            $this->_message .= "first name invalid!"."<br>";
            array_push($arrItemError, 'user_first_name');
        }

        if (!preg_match("/[a-zA-Z]/", $params['user']['user_last_name'])) {
            $this->_message .= "last name invalid!"."<br>";
            array_push($arrItemError, 'user_last_name');
        }
        
        if (!preg_match("/[a-zA-Z0-9]/", $params['user']['user_login_name']) || strlen($params['user']['user_login_name']) < 8) {
            $this->_message .= "login name must be 8 characters and include uppercase, lowercase, and number!"."<br>";
            array_push($arrItemError, 'user_login_name');
        }
        if (!$logic->validateMail($params['user']['user_email'])) {
            $this->_message .= "user email invalid!"."<br>";
            array_push($arrItemError, 'user_email');
        }
        if (strlen($params['user']['user_login_pass']) >= 8 && preg_match("/[a-zA-Z0-9]/", $params['user']['user_login_pass'])) {
            if ($params['user']['user_login_pass'] !== $params['user_login_pass_confirm']) {
                $this->_message .=" user login pass and user login pass confirm are dissimilarity
    !"."<br>";
                array_push($arrItemError, 'user_login_pass');
                array_push($arrItemError, 'user_login_pass_confirm');
            }
        } else {
            $this->_message .="Passwords must be 8 characters and include uppercase, lowercase, and number"."<br>";
            array_push($arrItemError, 'user_login_pass');
        }
        
        
        if ($this->_message != "") {
            $this->_intIsOk = -2; //err valdate
        }
        if ($this->_intIsOk == 1){
            $huyLib = new HuyLib_Mail();
            $huyLib->send();
        }
        $arrReponse = [
            "arrItemError" => $arrItemError,
            "message" => $this->_message,
            "intIsOk" => $this->_intIsOk
        ];
        echo json_encode($arrReponse);
    }
    
    public function confirmmailcodeAction($param) {
        
    }
}

