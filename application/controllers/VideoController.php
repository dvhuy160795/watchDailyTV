<?php

class VideoController extends Zend_Controller_Action
{
    protected $libAttachment;
    protected $builderAttachment;
    protected $dbAttachment;
    protected $dbVideo;
    protected $dbVideoType;
    protected $_dbUser;


    public function init()
    {
        $this->libAttachment = new HuyLib_AttachmentFile();
        $this->dbAttachment = new Application_Model_DbTable_Attachment();
        $this->builderAttachment = new Application_Model_Builder_Attachment();
        $this->dbVideo = new Application_Model_DbTable_Video();
        $this->_dbUser = new Application_Model_DbTable_User();
    }

    public function indexAction()
    {
        // action body
    }
   
    public function uploadAction(){

    }

    public function addvideoAction(){
    	$this->_helper->layout->disableLayout();
        $this->dbVideoType = new Application_Model_DbTable_VideoType();
        $aryConditionGetVideoType = [];
        $aryVideoType = [];
        $this->dbVideoType->getVideoTypeByConditionAnd($aryVideoType, $aryConditionGetVideoType);
        $this->view->aryVideoType = $aryVideoType;
    }

    public function savevideoAction(){
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $videoForm = new Application_Form_Video($params['video']);
        $message = "";
        if (!$videoForm->isValid($params['video'])){
            $aryMessageValid = $videoForm->getMessages();
            foreach ($aryMessageValid as $itemValid) {
                foreach ($itemValid as $value) {
                    $message .= $value.PHP_EOL;
                }
            }
            $respon = [
                "intIsOk" => $videoForm->isValid($params['video']),
                "message" => $message
            ];
            echo json_encode($respon);
            return;
        }
        $arrayFile = [];
        $arrayFileDone = [];
        $this->libAttachment->UploadAttachmentFile($arrayFile, $params['controller']);
        $aryVideo = [
            'video_code' => "",
            'video_title' => $params['video']['video_title'],
            'video_description' => $params['video']['video_description'],
            'video_url_image_alias' => isset($arrayFile['video_url_images_alias']['url_path']) ? $arrayFile['video_url_images_alias']['url_path'] : "",
            'video_url_video' => isset($arrayFile['video_url_video']['url_path']) ? $arrayFile['video_url_video']['url_path'] : "",
            'video_video_type_code' => $params['video']['video_type_code'],
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
    
    public function loadlistvideoAction() {
        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();

        $aryListVideo = [];
        $aryConditionGetVideo = [
            'video_type_account' => $_SESSION['user']['user_code'],
        ];
        $arrCondition['user_code'] = $_SESSION['user']['user_code'];
        $this->_dbUser->getUserByConditionByAnd($arrCondition,$arrResult);
        $isHasVideo = $this->dbVideo->getVideoByMailAndMoreByAND($aryListVideo,$aryConditionGetVideo);
        if (!$isHasVideo) {
           $aryListVideo = []; 
        }
        $paginator  = Zend_Paginator::factory($aryListVideo);
        $perPage = 3;
        $paginator->setDefaultItemCountPerPage($perPage);
        $allItems = $paginator->getTotalItemCount();
        $countPages = $paginator->count();
        $p = $this->getRequest()->getParam('p');
        if(isset($p)) {
            $paginator->setCurrentPageNumber($p); 
        } else {
            $paginator->setCurrentPageNumber(1);
        }
        $currentPage = $paginator->getCurrentPageNumber();
        $this->view->albums = $paginator;
        $this->view->countItems = $allItems;
        $this->view->countPages = $countPages;
        $this->view->currentPage = $currentPage;
        if($currentPage != $countPages)
        {
            $this->view->previousPage = $currentPage-1;
            $this->view->nextPage = $currentPage+1;
            $this->view->endPage = $countPages; 
        }
        else if($currentPage == 1)
        {
            $this->view->nextPage = $currentPage+1;
            $this->view->previousPage = 1; 
        }
        else if($currentPage == 0)
        {
            $this->view->firstPage = $currentPage;
        }
        else {
            $this->view->nextPage = $currentPage+1;
            $this->view->previousPage = $currentPage-1;
        }
        $this->view->hasNext = $currentPage < $countPages ? true : false;
        $this->view->hasPrev = $currentPage > 1 ? true : false;
        $this->view->hasFirst = $currentPage > 1 ? true : false;
        $this->view->hasEnd = $currentPage < $countPages ? true : false;
        $this->view->aryListVideo = $paginator;
        
//        $html = $this->view->render(
//            $this->controller . '/loadlistvideo'
//        );
//        $respon = [
//            "html" =>$html
//        ];
//        echo json_encode($respon);
    }
}

