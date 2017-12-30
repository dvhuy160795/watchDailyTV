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
    
    public function registerAction() {
        $this->_helper->layout->disableLayout();
    }

    public function loginAction() {
        $this->_helper->layout->disableLayout();
    }

    public function checkexistuserAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        var_dump($_FILE);
        var_dump($this->_request->getParams());die;
    }
}

