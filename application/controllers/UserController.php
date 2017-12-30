<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
//        $this->_helper->layout()->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function loginAction() {
        //$this->_helper->layout->disableLayout();
        //var_dump($this->view->_response);die;
        $this->getResponse()->appendBody(Zend_Json::encode("1111"));
    }
}

