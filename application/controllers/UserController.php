<?php

class UserController extends Zend_Controller_Action
{
    private $_message = "";
    private $_intIsOk = 1;
    protected $_dbUser;
    protected $_builderUser;
    protected $_builderAttachment;
    protected $_dbAttachment;
    protected $_logic;
    protected $_libAttachment;
    protected $dbCity;
    protected $dbDistrict;
    protected $dbStreet;
    protected $dbVideo ;

    public function init()
    {
        $this->_dbUser = new Application_Model_DbTable_User();
        $this->_builderUser = new Application_Model_Builder_User();
        $this->_builderAttachment = new Application_Model_Builder_Attachment();
        $this->_dbAttachment = new Application_Model_DbTable_Attachment;
        $this->_logic = new Application_Model_Logic();
        $this->_libAttachment = new HuyLib_AttachmentFile();
        $this->dbCity = new Application_Model_DbTable_City();
        $this->dbDistrict = new Application_Model_DbTable_District();
        $this->dbStreet = new Application_Model_DbTable_Street();
        $this->dbVideo = new Application_Model_DbTable_Video();
    }

    public function indexAction()
    {
        if (!isset($_SESSION['user'])) {
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(true);
            return;
        }
        $params = $this->_request->getParams();
        $aryListVideo = [];
        $arrCondition['user_code'] = $_SESSION['user']['user_code'];
        $this->_dbUser->getUserByConditionByAnd($arrCondition,$arrResult);
        $this->dbCity->getCityByConditionAnd($arrCity,['city_code' => $arrResult['user_city']]);
        $this->dbDistrict->getDistrictByConditionAnd($arrDistrict,['district_code' =>$arrResult['user_district']]);
        $this->dbStreet->getStreetByConditionAnd($arrStreet,['street_code' => $arrResult['user_address']]);
        $this->dbVideo->getVideoByConditionAnd($aryListVideo, ['video_type_account' => $_SESSION['user']['user_code']]);
        $this->view->aryUser = $arrResult;
        $this->view->arrCity = $arrCity[0];
        $this->view->arrDistrict = $arrDistrict[0];
        $this->view->arrStreet = $arrStreet[0];
        $this->view->aryListVideo = $aryListVideo;
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
            
            if ($fileInfo['avatarUser']['size'] > 100000) {
                $this->_message .= "size image alias need <= 100kb!"."<br>";
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
                $this->_message .=" user login pass and user login pass confirm are dissimilarity !"."<br>";
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
            'user_login_name' => $params['user']['user_login_name'],
        ];

        if ($this->_dbUser->getUserByMailAndMoreByOR($aryUserExist,$aryConditionUserExist,$userMail)) {
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
            $aryFile = [];
            $this->_libAttachment->UploadAttachmentFile($aryFile,$params['controller']);            
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

    public function showpopupforgotpasswordAction () {
        $this->_helper->layout->disableLayout();
    }

    public function checkregistercodesendedbymailAction () {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        
        if (isset($_COOKIE['user_code_register']) && $_COOKIE['user_code_register'] === hash("sha256",trim($params['codeByEmail'])) ) {
            //insert to wdt_user
            $aryUserForm = [
                'user_code_register' => $_COOKIE['user_code_register'],
                'user_email' => $_COOKIE['user_email'],
                'user_first_name' => $_COOKIE['user_first_name'],
                'user_last_name' => $_COOKIE['user_last_name'],
                'user_login_name' => $_COOKIE['user_login_name'],
                'user_login_pass' => $_COOKIE['user_login_pass']
            ];
            $this->_builderUser->buildDataBeforeInsertUser($aryUserForm,$params);
            $newIdUser = "";
            $err = [];
            $this->_dbUser->insertNewUser($aryUserForm, $newIdUser, $err);

            //insert to wtv_attachment
            if (isset($_COOKIE['isAttachment']) && $_COOKIE['isAttachment'] == true) {
                $nameFile = isset($_COOKIE['name']) ? $_COOKIE['name'] : "";
                $sizeFile = isset($_COOKIE['size']) ? $_COOKIE['size'] : null;
                $typeFile = isset($_COOKIE['type']) ? $_COOKIE['type'] : "";
                $aryAttachment = [
                    'attachment_url_source' => APPLICATION_PATH."/temp/".$params['module']."/".$params['controller']."/".$nameFile,
                    'attachment_file_name' => $nameFile,
                    'attachment_size' => $sizeFile,
                    'attachment_type' => $typeFile,
                    'attachment_type_upload_code' => "1",
                ];
                $newIdAttachment = "";
                $errAttachment = [];
                $this->_builderAttachment->buildDataBeforeInsertAttachment($aryAttachment,$params);
                $insertOk = $this->_dbAttachment->insertAttachment($aryAttachment,$newIdAttachment,$errAttachment);
                if ($insertOk != 1) {
                    var_dump($errAttachment);
                }
            }
            //unset Cookie
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

    public function sendmailforgotpasswordAction () {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $params = $this->_request->getParams();

        $aryUserExist = [];
        $aryConditionUserExist = [
            'user_login_name' => $params['user_login_name']
        ];
        $userMail = $params['user_email'];
        $userIsExist = $this->_dbUser->getUserByMailAndMoreByAND($aryUserExist,$aryConditionUserExist,$userMail);

        if ($userIsExist == false) {
            $this->_message = "Account is not exist!";
            $this->_intIsOk = -2;
            goto GOTO_LINE;
        }
        $newPassword = rand(10000000,99999999);
        $aryUserUpdate = [
            'user_login_pass' => $newPassword
        ];
        $condition = [];
        $intIsOk = $this->_dbUser->updateUserByCode($aryUserUpdate,$condition,$aryUserExist['user_code'],$err);
        if ($intIsOk == 1) {
            $huyLib = new HuyLib_Mail();
            $isSendMailSuccess = $huyLib->sendMailForgotPasword($params['user_email'], $newPassword);
        } else {
            var_dump($err);
        }

        GOTO_LINE:
        $arrReponse = [
            "message" => $this->_message,
            "intIsOk" => $this->_intIsOk
        ];
        echo json_encode($arrReponse);
    }

    public function loginexistuserAction () {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        $params = $this->_request->getParams();
        $condition = $params['user'];
        $arrResult = [];

        $isGetDataSuccess = $this->_dbUser->getUserByConditionByAnd($condition, $arrResult);
        if ($isGetDataSuccess == false) {
            $this->_message = "User name or password is wrong !";
            $this->_intIsOk = -2;
            goto GOTO_LINE;
        }
        // session_destroy();
        $_SESSION['user'] = [
            'user_name' => $arrResult['user_full_name'],
            'user_code' => $arrResult['user_code']
        ];
        GOTO_LINE:
        $arrReponse = [
            "message" => $this->_message,
            "intIsOk" => $this->_intIsOk
        ];
        echo json_encode($arrReponse);
    }

    public function showpopupeditAction () {
        $this->_helper->layout->disableLayout();
        $arrCurrentUser = [];
        $userCode = $_SESSION['user']['user_code'];
        $this->_dbUser->getOneUser($arrCurrentUser,[],$userCode);

        $arrCity = [];
        if ($this->dbCity->getListCity($arrCity)) {
            $this->view->citys = $arrCity;
        }
        $conditionDistrict = [
            
        ];
        $arrDistrict = [];
        if ($this->dbDistrict->getDistrictByConditionAnd($arrDistrict, $conditionDistrict)) {
            $this->view->citys = $arrCity;
        }
        $this->view->arrCurrentUser = $arrCurrentUser;
    }

    public function saveedituserAction () {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        
        $dataUpdate = [];
        $this->_builderUser->buildDataBeforeUpdateUser($dataUpdate,$params['user']);
        $error = [];
        $intIsOk = $this->_dbUser->updateUserByCode($dataUpdate, [], $_SESSION['user']['user_code'],$error);
        if (!empty($error)) {
            var_dump($error);die;
        }
        if ($intIsOk == 1){
            $arrReponse['intIsOk'] = $intIsOk;
            echo json_encode($arrReponse);
        }
    }
    
    public function logoutAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        unset($_SESSION['user']);
        $arrReponse['intIsOk'] = 1;
        echo json_encode($arrReponse);
    }
}

