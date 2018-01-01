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

        
        if (!preg_match("/[a-zA-Z]/", $params['user']['user_first_name'])) {
            $this->_message = "first name invalid!";
        }

        if (!preg_match("/[a-zA-Z]/", $params['user']['user_last_name'])) {
            $this->_message .= PHP_EOL."last name invalid!";
        }

        if (!preg_match("/[a-zA-Z0-9]/", $params['user']['user_login_name'])) {
            $this->_message .= PHP_EOL."login name invalid!";
        }
        if (!$logic->validateMail($params['user']['user_email'])) {
            $this->_message .= PHP_EOL."user email invalid!";
        }

        if ($params['user']['user_login_pass'] !== $params['user_login_pass_confirm']) {
            # code...
        }
        $params['user']['user_login_pass']
        $params['user_login_pass_confirm']
        $arrReponse = [
            $message = $this->_message,
            $intIsOk = $this->_intIsOk
        ];
        echo json_encode($arrReponse);
    }
}

