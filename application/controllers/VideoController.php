<?php

class VideoController extends Zend_Controller_Action
{
    protected $libAttachment;
    protected $builderAttachment;
    protected $dbAttachment;
    public function init()
    {
        $this->libAttachment = new HuyLib_AttachmentFile();
        $this->dbAttachment = new Application_Model_DbTable_Attachment();
        $this->builderAttachment = new Application_Model_Builder_Attachment();
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
        $arrayFile = [];
        $arrayFileDone = [];
        $this->libAttachment->UploadAttachmentFile($arrayFile, $params['controller']);
        [
            'id' => "",
            'attachment_name' => "",
            'attachment_url_source' => "",
            'attachment_file_name' => "",
            'attachment_size' => "",
            'attachment_type' => "",
            'attachment_type_upload' => "",
            'attachment_type_upload_code' => ""
        ];
        $this->builderAttachment->buildDataBeforeInsertAttachment($arrayFileDone, $arrayFile);
        $this->dbAttachment->insertAttachment($aryAttachment,$newIdAttachment);
    }
}

