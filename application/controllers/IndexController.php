<?php

class IndexController extends Zend_Controller_Action
{
	protected $logic;
	protected $dbCity;
    public function init()
    {
        $this->logic = new Application_Model_Logic();
        $this->dbCity = new Application_Model_DbTable_City();
    }

    public function indexAction()
    {
//        $this->_helper->layout()->disableLayout();
//        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function loadlistselectAction() {
    	$this->_helper->layout()->disableLayout();
    	$params = $this->_request->getParams();
    	$arrCity = [];
    	$arrSetView = [];
    	$this->dbCity->getListCity($arrCity);
    	$arrCityToView = $this->logic->getOneItemInArr($arrCity,'city_name');
    	$this->view->aryList = $arrCityToView;
    }

    public function loadlistdistrictAction() {
    	$params = $this->_request->getParams();
    	
    }

    public function loadliststreetAction() {
    	$params = $this->_request->getParams();
    	
    }
}

