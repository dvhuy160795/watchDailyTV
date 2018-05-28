<?php

class IndexController extends Zend_Controller_Action
{
    protected $logic;
    protected $dbCity;
    protected $dbDistrict;
    protected $dbStreet;
    protected $dbVideo;
    protected $dbVideoType;
    protected $dbUser;

    public function init()
    {
        $this->logic = new Application_Model_Logic();
        $this->dbCity = new Application_Model_DbTable_City();
        $this->dbDistrict = new Application_Model_DbTable_District();
        $this->dbStreet = new Application_Model_DbTable_Street();
        $this->dbVideo = new Application_Model_DbTable_Video();
        $this->dbVideoType = new Application_Model_DbTable_VideoType();
        $this->dbUser = new Application_Model_DbTable_User();
    }

    public function indexAction()
    {   
        $listAryVideoType = [];
        $aryUser = [];
        $this->dbUser->getMultiUser($aryUser, $condition = []);
        $aryConditionVideoType = [
            'video_type_is_view_list_home' => 1,
        ];
        $this->dbVideoType->getVideoTypeByConditionAnd($listAryVideoType, $aryConditionVideoType);
        
        foreach ($listAryVideoType as $value) {
            $listAryVideo = [];
            $aryConditionVideo = [
                'video_video_type_code' => $value['id'],
                'video_is_public'   => 1,
            ];
            $this->dbVideo->getVideoByMailAndMoreByAND($listAryVideo,$aryConditionVideo);
            $paginator  = Zend_Paginator::factory($listAryVideo);
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
            $this->view->{'countItems'.$value['id']} = $allItems;
            $this->view->{'countPages'.$value['id']} = $countPages;
            $this->view->{'currentPage'.$value['id']} = $currentPage;
            if($currentPage != $countPages)
            {
                $this->view->{'previousPage'.$value['id']} = $currentPage-1;
                $this->view->{'nextPage'.$value['id']} = $currentPage+1;
                $this->view->{'endPage'.$value['id']} = $countPages; 
            }
            else if($currentPage == 1)
            {
                $this->view->{'nextPage'.$value['id']} = $currentPage+1;
                $this->view->{'previousPage'.$value['id']} = 1; 
            }
            else if($currentPage == 0)
            {
                $this->view->{'firstPage'.$value['id']} = $currentPage;
            }
            else {
                $this->view->{'nextPage'.$value['id']} = $currentPage+1;
                $this->view->{'previousPage'.$value['id']} = $currentPage-1;
            }
            $this->view->{'hasNext'.$value['id']} = $currentPage < $countPages ? true : false;
            $this->view->{'hasPrev'.$value['id']} = $currentPage > 1 ? true : false;
            $this->view->{'hasFirst'.$value['id']} = $currentPage > 1 ? true : false;
            $this->view->{'hasEnd'.$value['id']} = $currentPage < $countPages ? true : false;
            $this->view->{'listAryVideo'.$value['id']} = $paginator;
        }
        $this->view->aryUser = $aryUser;
        $this->view->listAryVideoType =  $listAryVideoType;
    }

    public function loadlistselectAction() {
    	$this->_helper->layout()->disableLayout();
    	$params = $this->_request->getParams();
    	$arrSelect = [];
    	$arrSetView = [];
        $aryResult = [];
        $typeSelect = "";
        if ($params['address'] == 'user_city') {
            $arrCondition = [
                "district_city_code" => $params['code']
            ];
            $this->dbDistrict->getDistrictByConditionAnd($aryResult,$arrCondition);
            $typeSelect = "district";
        }
        if ($params['address'] == 'user_district') {
            $arrCondition = [
                "street_district_code" => $params['code']
            ];
            $this->dbStreet->getStreetByConditionAnd($aryResult,$arrCondition);
            $typeSelect = "street";
        }
    	$this->view->aryResult = $aryResult;
        $this->view->typeSelect = $typeSelect;
    }
    
    public function listvideobymenuAction() {
        $this->_helper->layout()->disableLayout();
    	$params = $this->_request->getParams();
        $aryListVideo = [];
        $aryConditionGetVideo = [
            'video_is_public'   => 1,
        ];
        if (isset($params['value'])) {
            $order = $params['value']." DESC";
            $_SESSION['order_video'] = $params['value'];
        } else {
            $order = $_SESSION['order_video']." DESC";
        }
        
        $isHasVideo = $this->dbVideo->getVideoByConditionAndOrder($aryListVideo,$aryConditionGetVideo, $order);
        if (!$isHasVideo) {
           $aryListVideo = []; 
        }
        $paginator  = Zend_Paginator::factory($aryListVideo);
        $perPage = 20;
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
}

