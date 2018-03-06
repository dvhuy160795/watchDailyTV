<?php

class IndexController extends Zend_Controller_Action
{
	protected $logic;
	protected $dbCity;
    protected $dbDistrict;
    protected $dbStreet;

    public function init()
    {
        $this->logic = new Application_Model_Logic();
        $this->dbCity = new Application_Model_DbTable_City();
        $this->dbDistrict = new Application_Model_DbTable_District();
        $this->dbStreet = new Application_Model_DbTable_Street();
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

}

