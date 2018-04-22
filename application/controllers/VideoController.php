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
    	$this->_helper->layout->disableLayout();
    }

    public function savevideoAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $libAttachment = new HuyLib_AttachmentFile();
        $libAttachment->UploadAttachmentFile($params['controller']);
    }
}

