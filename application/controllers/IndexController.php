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
    	$arrSelect = [];
    	$arrSetView = [];
        switch ($params['address']) {
            case 'value':
                # code...
                break;
            
            default:
                # code...
                break;
        }
    	$this->dbCity->getListCity($arrSelect);
    	$arrToView = $this->logic->getOneItemInArr($arrSelect,'city_name');
    	$this->view->aryList = $arrToView;
    }

}

