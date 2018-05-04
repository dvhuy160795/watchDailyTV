<?php

class VideoController extends Zend_Controller_Action
{
    protected $libAttachment;
    protected $builderAttachment;
    protected $dbAttachment;
    protected $dbVideo;
    public function init()
    {
        $this->libAttachment = new HuyLib_AttachmentFile();
        $this->dbAttachment = new Application_Model_DbTable_Attachment();
        $this->builderAttachment = new Application_Model_Builder_Attachment();
        $this->dbVideo = new Application_Model_DbTable_Video();
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
        $aryVideo = [
            'video_code' => "",
            'video_title' => $params['video']['video_title'],
            'video_description' => $params['video']['video_description'],
            'video_url_image_alias' => isset($arrayFile['video_url_images_alias']['url_path']) ? $arrayFile['video_url_images_alias']['url_path'] : "",
            'video_url_video' => isset($arrayFile['video_url_video']['url_path']) ? $arrayFile['video_url_video']['url_path'] : "",
            'video_video_type_code' => isset($arrayFile['video_url_video']['type']) ? $arrayFile['video_url_video']['type'] : "",
            'video_size' => isset($arrayFile['video_url_video']['size']) ? $arrayFile['video_url_video']['size'] : "0",
            'video_view' => 1,
            'video_type_account' => $_SESSION['user']['user_code'],
            'video_is_deleted' => 0,
            'created' => date("Y/m/d")
        ];
        $intIsOk = $this->dbVideo->insertNewVideo($aryVideo, $newIdVideo, $err);
        $message = "Upload success!!";
        if ($intIsOk != 1) {
            var_dump($err);die;
        }
        $respon = [
            "intIsOk" => $intIsOk,
            "message" => $message
        ];
        echo json_encode($respon);
    }
    
    public function viewAction() {
        $params = $this->_request->getParams();
        $aryVideo = [];
        $condition = [
            'id' => $params['id'],
        ];
        $this->dbVideo->getOneVideoByMailAndMoreByAND($aryVideo, $condition);
        $this->view->aryVideo = $aryVideo;
    }
}

