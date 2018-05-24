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

    public function add() {
        
    }
    
    public function save() {
        
    }
    
    public function loadlistvideobyuserAction() {
        $this->_helper->layout()->disableLayout();
        $_SESSION['user']['user_code'];
    }
}

