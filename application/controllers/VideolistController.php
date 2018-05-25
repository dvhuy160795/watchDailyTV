<?php

class VideoListController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction() {
        $this->_helper->layout()->disableLayout();
    }
    
    public function saveAction() {
        $this->_helper->layout()->disableLayout();
    }
    
    public function loadlistvideobyuserAction() {
        $this->_helper->layout()->disableLayout();
        $_SESSION['user']['user_code'];
    }
}

