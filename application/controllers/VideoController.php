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
    protected $logic;
    protected $dbLike;

    public function init()
    {
        $this->libAttachment = new HuyLib_AttachmentFile();
        $this->dbAttachment = new Application_Model_DbTable_Attachment();
        $this->builderAttachment = new Application_Model_Builder_Attachment();
        $this->dbVideo = new Application_Model_DbTable_Video();
        $this->_dbUser = new Application_Model_DbTable_User();
        $this->dbComment = new Application_Model_DbTable_Comment();
        $this->dbVideoType = new Application_Model_DbTable_VideoType();
        $this->dbLike = new Application_Model_DbTable_Like();
        $this->logic = new Application_Model_Logic();
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
        $aryVideoUpdate = [
            'video_view' => $aryVideo['video_view'] + 1
        ];
        $this->dbVideo->updateVideoByCode($aryVideoUpdate, $conditionUpdate = [], $params['id'],$err);
        $conditionComment = [
            'comment_video_code' => $aryVideo['video_code']
        ];
        $this->dbComment->getCommentByConditionAnd($aryListComment, $conditionComment);
        $conditionLike = [
            'like_user_code' =>  $_SESSION['user']['user_code'],
            'like_video_code'=> $params['id']
        ];
        $this->dbLike->getLikeByConditionAnd($aryLike, $conditionLike);
        
        //get total like
        $conditionTotalLike = [
            'like_video_code'=> $params['id'],
            'like_is_like' => 1,
            'like_is_choose' => 1
        ];
        $this->dbLike->getLikeByConditionAnd($aryTotalLike, $conditionTotalLike);
        $this->view->totalLike = count($aryTotalLike);
        //get total dislike
        $conditionTotalDisLike = [
            'like_video_code'=> $params['id'],
            'like_is_like' => 0,
            'like_is_choose' => 1
        ];
        $this->dbLike->getLikeByConditionAnd($aryTotalDisLike, $conditionTotalDisLike);
        $this->view->totalDisLike = count($aryTotalDisLike);
        //get video in list
        $conditionGetListVideoInList = [
            'video_list_code' => $aryVideo['video_list_code'],
        ];
        $this->dbVideo->getVideoByMailAndMoreByAND($aryListVideoInList, $conditionGetListVideoInList);
        //get listVideo
        $conditionGetListVideo = [
            'video_video_type_code' => $aryVideo['video_video_type_code'],
            'video_type_account' => $aryVideo['video_type_account'],
        ];
        $this->dbVideo->getVideoByMailAndMoreByOR($aryListVideoLike, $conditionGetListVideo);
        if (isset($_SESSION['user']['user_code'])) {
            $this->view->currentUser = $_SESSION['user']['user_code'];   
        }
        if (isset($_SESSION['user'])) {
             $isLogin = 1;
        }else { $isLogin = 0;}
        $this->view->aryVideo = $aryVideo;
        $this->view->aryListComment = $aryListComment;
        $this->view->aryListVideoLike  = $aryListVideoLike;
        $this->view->aryListVideoInList = $aryListVideoInList;
        $this->view->aryUser = $aryUser;
        $this->view->isLogin  = $isLogin;
        $this->view->aryLike = isset($aryLike[0]) ? $aryLike[0] : [];
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
        $aryUser = [];
        $this->_dbUser->getMultiUser($aryUser, $condition = []);
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
        $this->view->aryUser = $aryUser;
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
        $params = $this->_request->getParams();
        $aryListComment = [];
        
        $aryUser = [];
        $this->_dbUser->getUserByConditionByAnd([], $aryUser);

        $conditionComment = [
            'comment_video_code' => $params['videoCode']
        ];
        $this->dbComment->getCommentByConditionAnd($aryListComment, $conditionComment);

        $this->view->aryListComment = $aryListComment;
        $this->view->aryUser = $aryUser;
    }
    
    public function searchvideoAction() {
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();
        $conditionUser = [
            'user_full_name' =>  $params['value']
        ];
        $aryListUserCode = [];
        $this->_dbUser->getMultiUserConditionLike($aryListUserCode, $conditionUser);
        $conditionTypeVideo = [
            'video_type_title' => $params['value']
        ];
        $aryListTypeCode = [];
        $this->dbVideoType->getMultiVideoTypeConditionLike($aryListTypeCode, $conditionTypeVideo);
        $paramCondition = [
            'video_type_account' => $aryListUserCode,
            'video_video_type_code' => $aryListTypeCode,
        ];
        $sqlCondition = $this->dbVideo->buildSqlConditionSearchVideo($paramCondition,$params['value']);
        $fieldVideo = [
            "*"
        ];
        $aryListVideo = $this->dbVideo->getVideoByWhere($sqlCondition,$fieldVideo);
        $this->view->aryListVideo = $aryListVideo;
    }
    
    public function loadvideobylistAction() {
        $this->_helper->layout->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();

        $aryListVideo = [];
        $aryConditionGetVideo = [
            'video_type_account' => $_SESSION['user']['user_code'],
            'video_list_code'   => (isset($params['video_list_code'])) ? $params['video_list_code'] : $params['typeCode'],
        ];
        $this->view->video_list_code = (isset($params['video_list_code'])) ? $params['video_list_code'] : $params['typeCode'];
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
    }
    
    public function likevideoAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $aryLike = [];
        if (!isset($_SESSION['user'])) {
            $respon = [
                "intIsOk" => 2,
                "message"  => "please login!",
            ];
            echo json_encode($respon);
            return;
        }
        $conditionLike = [
            'like_user_code' => $_SESSION['user']['user_code'],
            'like_video_code' => $params['idVideo'],
        ];
        $isHasData = $this->dbLike->getLikeByConditionAnd($aryLike, $conditionLike);
        //add
        if ($isHasData == false) {
            $aryLike = [
                'like_user_code' => $_SESSION['user']['user_code'],
                'like_video_code' => $params['idVideo'],
                'like_is_like' => $params['isLike'],
                'like_is_deleted' => 0,
                'created' => date('Y/m/d'),
                'like_is_choose' => 1,
            ];
            $this->dbLike->insertLike($aryLike, $newIdLike, $err);
        } else { // update like
            //Like
            $aryLikeIsset = [];
            $conditionLikeIsset = [
                'like_user_code' => $_SESSION['user']['user_code'],
                'like_video_code' => $params['idVideo'],
            ];
            $isHasDataIsset = $this->dbLike->getLikeByConditionAnd($aryLikeIsset, $conditionLikeIsset);
            if ($params['isLike'] == 1) {
                //click laij btn like
                if ($aryLikeIsset[0]['like_is_choose'] == 1 && $aryLikeIsset[0]['like_is_like'] != 0) {
                    $aryUpdateLike = [
                        'like_is_like' => $params['isLike'],
                        'like_is_choose' => 0,
                    ];
                } else {
                    $aryUpdateLike = [
                        'like_is_like' => $params['isLike'],
                        'like_is_choose' => 1,
                    ];
                }
                
                $conditionLike = [
                    'like_user_code' => $_SESSION['user']['user_code'],
                    'like_video_code' => $params['idVideo'],
                    'like_is_choose' => 1,
                ];
                $this->dbLike->updateLike($aryUpdateLike, $conditionLike = [], $aryLikeIsset[0]['id'], $err);
            }
            //dislike
            if ($params['isLike'] == 0) {
                //click laij btn dislike
                if ($aryLikeIsset[0]['like_is_choose'] == 1 && $aryLikeIsset[0]['like_is_like'] != 1) {
                    $aryUpdateLike = [
                        'like_is_like' => $params['isLike'],
                        'like_is_choose' => 0,
                    ];
                } else {
                    $aryUpdateLike = [
                        'like_is_like' => $params['isLike'],
                        'like_is_choose' => 1,
                    ];
                }
                
                $conditionLike = [
                    'like_user_code' => $_SESSION['user']['user_code'],
                    'like_video_code' => $params['idVideo'],
                    'like_is_choose' => 1,
                ];
                $this->dbLike->updateLike($aryUpdateLike, $conditionLike = [], $aryLikeIsset[0]['id'], $err);
            }
        }
        
        //get total like
        $conditionTotalLike = [
            'like_video_code'=> $params['idVideo'],
            'like_is_like' => 1,
            'like_is_choose' => 1
        ];
        $this->dbLike->getLikeByConditionAnd($aryTotalLike, $conditionTotalLike);
        
        //get total dislike
        $conditionTotalDisLike = [
            'like_video_code'=> $params['idVideo'],
            'like_is_like' => 0,
            'like_is_choose' => 1
        ];
        $this->dbLike->getLikeByConditionAnd($aryTotalDisLike, $conditionTotalDisLike);
        
        $respon = [
            "totalLike" => count($aryTotalLike),
            "totalDisLike"  => count($aryTotalDisLike),
        ];
        echo json_encode($respon);
    }

    public function removevideotolistAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $isOk = 1;
        if (isset($params['id'])) {
            $aryVideoUpdate = [
                'video_list_code' => ""
            ];
            $isOk = $this->dbVideo->updateVideoByCode($aryVideoUpdate, $condition = [],$params['id'],$err);
        }
        $respon = [
            "isOk" => $isOk
        ];
        echo json_encode($respon);
    }
    
    public function addvideotolistAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $isOk = 1;
        if (isset($params['id'])) {
            $aryVideoUpdate = [
                'video_list_code' => $params['idList']
            ];
            $isOk = $this->dbVideo->updateVideoByCode($aryVideoUpdate, $condition = [],$params['id'],$err);
        }
        $respon = [
            "isOk" => $isOk
        ];
        echo json_encode($respon);
    }
    
    public function loadvideosnotinlistAction() {
        $this->_helper->layout->disableLayout();
        $params = $this->_request->getParams();
        $aryListVideoNotInList = [];
        $conditionVideoNotInList =  [];
        $conditionVideoNotInList['video_list_code'] = (isset($params['idList']) && $params['idList'] !== "") ? $params['idList'] : "";
        $conditionVideoNotInList['video_type_account'] = $_SESSION['user']['user_code'];
        if (isset($params['idList']) && $params['idList'] !== "") {
            $this->dbVideo->getVideoNotInListByConditionAndOrderLimitInAddLIstVideo($aryListVideoNotInList, $conditionVideoNotInList);
        }
        $this->view->aryListVideoNotInList = $aryListVideoNotInList;
    }
    
    public function loadvideosinlistAction() {
        $this->_helper->layout->disableLayout();
        $aryVideosInlist = [];
        $params = $this->_request->getParams();
        if (isset($params['idList']) && $params['idList'] !== "") {
            $conditionVideosInlist = [
                'video_list_code' => $params['idList']
            ];
            $this->dbVideo->getVideoByMailAndMoreByAND($aryVideosInlist, $conditionVideosInlist);
        }
        $this->view->aryVideosInlist = $aryVideosInlist;
    }
    
    public function deletevideoAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $aryVideoUpdate = [
            'video_is_deleted' => 1,
        ];
        $isOk = $this->dbVideo->updateVideoByCode($aryVideoUpdate, $condition = [],$params['idVideo'],$err = []);
    }
}

