<?php

class UserController extends Zend_Controller_Action
{
    private $_message = "";
    private $_intIsOk = 1;
    protected $_db;
    protected $_builderUser;
    protected $_builderAttachment;
    protected $_logic;

    public function init()
    {
        $this->_db = new Application_Model_DbTable_User();
        $this->_builderUser = new Application_Model_Builder_User();
        $this->_builderAttachment = new Application_Model_Builder_Attachment();
        $this->_logic = new Application_Model_Logic();
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

        $params = $this->_request->getParams();
        $fileInfo = $file->getFileInfo();
        
        $aryUserInfo = $params['user'];
        $arrItemError = [];
        $arrTypeFileCondition = [
            "image/png",
            "image/gif",
            "image/jpg",
            "image/jpeg"
        ];
        // validate form
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
        if (!$this->_logic->validateMail($params['user']['user_email'])) {
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
            goto GOTO_LINE;
        }
        //check exist user and mail
        $aryUserExist = [];
        $userMail = $params['user']['user_email'];
        $aryConditionUserExist = [
            'user_login_name' => $params['user']['user_login_name']
        ];

        if ($this->_db->getUserByMailAndMoreByOR($aryUserExist,$aryConditionUserExist,$userMail)) {
            array_push($arrItemError, 'user_login_name');
            array_push($arrItemError, 'user_email');
            $this->_message = "Register name or email address used !!".PHP_EOL."Please choose a different name or different email !";
            $this->_intIsOk = -2; //err valdate
            goto GOTO_LINE;
        }

        //validate success
        if ($this->_intIsOk == 1){
            $params['codeByEmail'] = rand(100000,999999);
            $this->_builderUser->setAryCookieBeforeCheckCodeEmail($params);
            $this->_builderAttachment->setCookieToAryAttachment($fileInfo);
            $huyLib = new HuyLib_Mail();
            $isSendMailSuccess = $huyLib->sendMailRegisterUser($params['user']['user_email'], $params['codeByEmail']);
            //send mail faild
            if (!$isSendMailSuccess) {
                $this->_intIsOk = -2;
                $this->_message = "Have problems sending mail !!";
                goto GOTO_LINE;
            }
        }
        GOTO_LINE:
        $arrReponse = [
            "arrItemError" => $arrItemError,
            "message" => $this->_message,
            "intIsOk" => $this->_intIsOk
        ];
        echo json_encode($arrReponse);
    }

    public function showpopupcheckcodeAction () {
        $this->_helper->layout->disableLayout();
    }

    public function checkregistercodesendedbymailAction () {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        
        if (isset($_COOKIE['user_code_register']) && $_COOKIE['user_code_register'] === hash("sha256",trim($params['codeByEmail'])) ) {
            $aryUserForm = $_COOKIE;
            $this->_builderUser->buildDataBeforeInsertUser($aryUserForm,$params);
            $newIdUser = "";
            $err = [];
            $this->_db->insertNewUser($aryUserForm, $newIdUser, $err);

            foreach ($_COOKIE as $key => $value) {
                setcookie($key,"", time() - 360);
            }
            $intIsOk = true;
        } else {
            setcookie("user_code_register","", time() - 360);
            $this->_message = "Code invalid!!!";
            $intIsOk = false;
        }
        $respon = [
            "intIsOk" => $intIsOk,
            "message" => $this->_message
        ];
        echo json_encode($respon);
    }
}

