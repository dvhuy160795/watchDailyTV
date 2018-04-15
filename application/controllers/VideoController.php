<?php

class VideoController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }
   
    public function uploadAction(){

    }

    public function addvideoAction(){
    	// $this->_helper->layout->disableLayout();
    }

    public function savevideoAction(){
        var_dump($_FILES);
    	var_dump($this->_request->getParams());die;
    }
}

