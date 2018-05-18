<?php

class VideoController extends Zend_Controller_Action
{
    protected $libAttachment;
    protected $builderAttachment;
    protected $dbAttachment;
    protected $dbVideo;
    protected $dbVideoType;
    protected $_dbUser;
    protected $dbComment;

    public function init()
    {
        $this->libAttachment = new HuyLib_AttachmentFile();
        $this->dbAttachment = new Application_Model_DbTable_Attachment();
        $this->builderAttachment = new Application_Model_Builder_Attachment();
        $this->dbVideo = new Application_Model_DbTable_Video();
        $this->_dbUser = new Application_Model_DbTable_User();
        $this->dbComment = new Application_Model_DbTable_Comment();
        $this->dbVideoType = new Application_Model_DbTable_VideoType();
    }

    public function indexAction()
    {
        // action body
    }
   
    public function uploadAction(){

    }

    public function addvideoAction(){
    	$this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();
        if (isset($params['id']) && $params['id'] !== "") {
            $aryVideo = [];
            $condition = [
                'id' => $params['id'],
            ];
            $this->dbVideo->getOneVideoByMailAndMoreByAND($aryVideo, $condition);
            $this->view->aryVideo = $aryVideo;
        }
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
        $libDataBase =  new HuyLib_DataBase();
        
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
        if (isset($params['id']) && $params['id'] !== "") {
            $aryVideo = [
                'video_title' => $params['video']['video_title'],
                'video_description' => $params['video']['video_description'],
                'video_url_image_alias' => isset($arrayFile['video_url_images_alias']['url_path']) ? $arrayFile['video_url_images_alias']['url_path'] : $params['video']['video_url_img_hidden'],
                'video_url_video' => isset($arrayFile['video_url_video']['url_path']) ? $arrayFile['video_url_video']['url_path'] : $params['video']['video_url_video_hidden'],
                'video_video_type_code' => $params['video']['video_type_code'],
                'video_size' => isset($arrayFile['video_url_video']['size']) ? $arrayFile['video_url_video']['size'] : "0",
                'video_is_public' => (isset($params['video']['video_is_public'])) ? $params['video']['video_is_public'] : 0,
            ];
            $intIsOk = $this->dbVideo->updateVideoByCode($aryVideo, [],$params['id'],$err);
            $message = "Edit success!!";
        } else {
            $code = $libDataBase->buildCodeInsertByDateTime();
            $aryVideo = [
                'video_code' => "VIDEO".$code,
                'video_title' => $params['video']['video_title'],
                'video_description' => $params['video']['video_description'],
                'video_url_image_alias' => isset($arrayFile['video_url_images_alias']['url_path']) ? $arrayFile['video_url_images_alias']['url_path'] : "",
                'video_url_video' => isset($arrayFile['video_url_video']['url_path']) ? $arrayFile['video_url_video']['url_path'] : "",
                'video_video_type_code' => $params['video']['video_type_code'],
                'video_size' => isset($arrayFile['video_url_video']['size']) ? $arrayFile['video_url_video']['size'] : "0",
                'video_view' => 1,
                'video_is_public' => (isset($params['video']['video_is_public'])) ? $params['video']['video_is_public'] : 0,
                'video_type_account' => $_SESSION['user']['user_code'],
                'video_is_deleted' => 0,
                'created' => date("Y/m/d")
            ];
            $intIsOk = $this->dbVideo->insertNewVideo($aryVideo, $newIdVideo, $err);
            $message = "Upload success!!";
        }
        
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
        $aryListComment = [];
        
        $aryUser = [];
        $this->_dbUser->getUserByConditionByAnd([], $aryUser);
        
        $this->dbVideo->getOneVideoByMailAndMoreByAND($aryVideo, $condition);
        $conditionComment = [
            'comment_video_code' => $aryVideo['video_code']
        ];
        $this->dbComment->getCommentByConditionAnd($aryListComment, $conditionComment);
        $conditionGetListVideo = [
            'video_video_type_code' => $aryVideo['video_video_type_code'],
            'video_type_account' => $aryVideo['video_type_account'],
        ];
        $this->dbVideo->getVideoByMailAndMoreByOR($aryListVideoLike, $conditionGetListVideo);
        if (isset($_SESSION['user']['user_code'])) {
            $this->view->currentUser = $_SESSION['user']['user_code'];   
        }
        $this->view->aryVideo = $aryVideo;
        $this->view->aryListComment = $aryListComment;
        $this->view->aryListVideoLike  = $aryListVideoLike;
        echo "<pre>";
        var_dump($aryUser);die;
        $this->view->aryUser = $aryUser;
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
    
    public function loadlistvideobyhomeAction() {
        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();

        $aryListVideo = [];
        $aryConditionGetVideo = [
            'video_video_type_code' => $params['typeCode'],
            'video_is_public'   => 1,
        ];
        $isHasVideo = $this->dbVideo->getVideoByMailAndMoreByAND($aryListVideo,$aryConditionGetVideo);
        if (!$isHasVideo) {
           $aryListVideo = []; 
        }
        $paginator  = Zend_Paginator::factory($aryListVideo);
        $perPage = 4;
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
        $this->view->typeCodeVideo = $params['typeCode'];
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
    }
    
    public function sendcommentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $param = $this->_request->getParams();
        $libDataBase =  new HuyLib_DataBase();
        $code = $libDataBase->buildCodeInsertByDateTime();
        $aryComment = [
            'comment_id' => "1",
            'comment_code' => "COMMENT".$code,
            'comment_user_code' => $_SESSION['user']['user_code'],
            'comment_video_code' => $param['videoCode'],
            'comment_content' => $param['comment'],
            'comment_is_deleted' => 0,
            'update' => '2000-01-01',
            'delete' => '2000-01-01',
            'created' => date("Y/m/d")
        ];
        if ($param['comment'] !== "") {
            $intIsOk = $this->dbComment->insertNewComment($aryComment, $newIdComment, $err);
        } else {
            $intIsOk = -2;
        }
        $respon = [
            "intIsOk" =>$intIsOk
        ];
        echo json_encode($respon);
    }
    
    public function loadlistcommentAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $aryListComment = [];
        $conditionComment = [
            'comment_video_code' => $params['videoCode']
        ];
        
        $intIsOk = $this->dbComment->getCommentByConditionAnd($aryListComment, $conditionComment);
        $html = "";
        foreach ($aryListComment as $comment) { 
            $condition = [
                'user_code' => $comment['comment_user_code']
            ];
            $aryUser = [];
            $this->_dbUser->getOneUserByIsDelete($aryUser, $condition,"");
//            $styleFloat = ($comment['comment_user_code'] == $_SESSION['user']['user_code']) ? 'float: right': '';
            $styleFloat = "";
            $html .= '<div>
                        <div style=" '.$styleFloat.'">
                            <span style="width:80%; font-size: 18px; font-weight: 800">'.$comment['comment_content'].'</span>  
                            <br><span style="width:20%">'.$comment['created'].'</span>
                            <br><font>'.$aryUser['user_full_name'].'</font>
                        </div>
                    </div>';
        }
        $respon = [
            "intIsOk" =>$intIsOk,
            "html"  => $html,
        ];
        echo json_encode($respon);
    }
}

