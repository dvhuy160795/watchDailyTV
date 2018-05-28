<?php

class VideolistController extends Zend_Controller_Action
{
    protected $dbVideo;
    protected $dbListVideo;
    public function init()
    {
        $this->dbVideo = new Application_Model_DbTable_Video();
        $this->dbListVideo = new Application_Model_DbTable_ListVideo();
    }

    public function indexAction()
    {
        // action body
    }

    public function addAction() {
        $this->_helper->layout()->disableLayout();
        $params = $this->_request->getParams();
        $aryListVideo = [];
        $aryVideosInlist = [];
        if (isset($params['idList']) && $params['idList'] !== "") {
            $conditionListVideo = [
                'id' => $params['idList']
            ];
            $this->dbListVideo->getGroupVideoByConditionAnd($aryListVideo, $conditionListVideo); 
            $conditionVideosInlist = [
                'video_list_code' => $params['idList']
            ];
            $this->dbVideo->getVideoByMailAndMoreByAND($aryVideosInlist, $conditionVideosInlist);
        }
        $aryListVideoNotInList = [];
        $conditionVideoNotInList =  [];
        $conditionVideoNotInList['video_list_code'] = (isset($params['idList']) && $params['idList'] !== "") ? $params['idList'] : "";
        $conditionVideoNotInList['video_type_account'] = $_SESSION['user']['user_code'];
        $this->dbVideo->getVideoNotInListByConditionAndOrderLimitInAddLIstVideo($aryListVideoNotInList, $conditionVideoNotInList);
        $this->view->aryListVideoNotInList = $aryListVideoNotInList;
        $this->view->aryListVideo = $aryListVideo[0];
        $this->view->aryVideosInlist = $aryVideosInlist;
    }
    
    public function saveAction() {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        if (isset($params['id_list_video']) && $params['id_list_video'] !== "") {
            $aryGroupVideo['video_group_status'] = isset($params['list_video']['video_group_status']) ? $params['list_video']['video_group_status'] : 0;
            $aryGroupVideo['video_group_group_code'] = $params['list_video']['video_group_group_code'];
            $aryGroupVideo['video_group_video_code'] = rand(0, 100000);
            $aryGroupVideo['update'] = date('Y/m/d');
            $codeListVideo = $params['id_list_video'];
            $isOk = $this->dbListVideo->updateListVideoByCode($aryGroupVideo,$conditionListVideo = [], $codeListVideo,$err);
            if ($isOk != 1) {
                var_dump($err);die;
            }
//            if (isset($params['listVideoAdd']) && $params['listVideoAdd'] !== "") {
//                foreach ($params['listVideoAdd'] as $key => $value) {
//                    $aryVideoUpdate = [
//                        'video_list_code' => $params['id_list_video'],
//                    ];
//                    $this->dbVideo->updateVideoByCode($aryVideoUpdate, $condition = [],$value,$err);
//                }
//            }
            $message = "Update list success!";
        } else {
            $params['list_video']['video_group_status'] = isset($params['list_video']['video_group_status']) ? $params['list_video']['video_group_status'] : 0;
            $aryGroupVideo = $params['list_video'];
            $aryGroupVideo['video_group_id'] = $_SESSION['user']['user_code'];
            $aryGroupVideo['created'] = date('Y/m/d');
            $isOk = $this->dbListVideo->insertNewListVideo($aryGroupVideo, $newIdGroupVideo, $err);
            if ($isOk != 1) {
                var_dump($err);die;
            }
            if (isset($params['listVideoAdd']) && $params['listVideoAdd'] !== "") {
                foreach ($params['listVideoAdd'] as $key => $value) {
                    $aryVideoUpdate = [
                        'video_list_code' => $newIdGroupVideo
                    ];
                    $this->dbVideo->updateVideoByCode($aryVideoUpdate, $condition = [],$value,$err);
                }
            }
            
            $message = "Insert list success!";
        }
        $respon = [
            "intIsOk" => $isOk,
            "message" => $message
        ];
        echo json_encode($respon);
    }
    
    public function loadlistvideobyuserAction() {
        $this->_helper->layout()->disableLayout();
        $conditionListVideo = [
            'video_group_id' => $_SESSION['user']['user_code']
        ];
        $this->dbListVideo->getGroupVideoByConditionAnd($aryListVideos, $conditionListVideo);
        $this->view->aryListVideos = $aryListVideos;
    }
    
    public function deletelistvideoAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $params = $this->_request->getParams();
        $aryListVideoUpdate = [
            'video_is_deleted' => 1,
        ];
        $isOk = $this->dbListVideo->updateListVideoByCode($aryListVideoUpdate, $condition = [],$params['idList'],$err = []);
    }
}

