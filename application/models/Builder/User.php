<?php

class Application_Model_Builder_User
{
	protected $_HuyLibCookie;
	protected $dbCity;

	public function __construct() {
		$this->dbCity = new Application_Model_DbTable_City();
	}
	public function buildDataBeforeInsertUser (&$aryData, $aryParams) {
		$libDataBase =  new HuyLib_DataBase();
		$code = $libDataBase->buildCodeInsertByDateTime();
		$aryMiss = [
			'user_code' => "USER".$code,
			'user_full_name' => $aryData['user_first_name']." ".$aryData['user_last_name'],
			'user_phone' => "",
			'user_city' => "",
			'user_district' => "",
			'user_address' => "",
			'user_birthday' => null,
			'user_url_image_alias' => "",
			'user_url_background' => "",
			'user_jog_present' => "",
                        'user_birthday'     => date("Y/m/d"),
			'user_type_account' => 1,
			'user_is_deleted' => 0,
			'created' => date("Y/m/d"),
			'user_code_register' => $aryParams['codeByEmail']
		];

		$aryData = array_merge($aryData,$aryMiss);
		// var_dump($aryData);
		// var_dump($aryParams);die;
		return $intIsOk = 1;
	}

        public function buildDataBeforeUpdateUser (&$aryData, $aryParams) {
		$aryData = [
			'user_full_name' => $aryParams['user_first_name']." ".$aryParams['user_last_name'],
			'user_phone' => $aryParams['user_phone'],
			'user_city' => $aryParams['user_city'],
			'user_district' => $aryParams['user_district'],
			'user_address' => $aryParams['user_street'],
			'user_birthday' => $aryParams['birth-year'].'-'.$aryParams['birth-month'].'-'.$aryParams['birth-day'],
			'user_jog_present' => $aryParams['user_jog_present'],
			'user_type_account' => 1,
			'user_is_deleted' => 0,
			'created' => date("Y/m/d"),
		];
		return $intIsOk = 1;
	}
        
	public function setAryCookieBeforeCheckCodeEmail($params) {
		$this->_HuyLibCookie = new HuyLib_Cookie();
		$mailCode['user_code_register'] = $params['codeByEmail'];
		$this->_HuyLibCookie->setCookieFiveMinute($params['user'],$mailCode);
	}

	public function getDataCity (&$aryResult) {
		$aryResult = $this->dbCity->getListCity();
	}

        
}