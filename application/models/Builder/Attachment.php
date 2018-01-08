<?php

class Application_Model_Builder_Attachment
{
	protected $_HuyLibCookie;
	public function __construct() {
		
	}
	public function buildDataBeforeInsertAttachment (&$aryData, $aryParams) {
		$libDataBase =  new HuyLib_DataBase();
		$code = $libDataBase->buildCodeInsertByDateTime();
		$aryMiss = [
			'user_code' => "USER".$code,
			'user_full_name' => $aryData['user_first_name']." ".$aryData['user_last_name'],
			'user_phone' => "",
			'user_city' => "",
			'user_district' => "",
			'user_address' => "",
			'user_birthday' => date("Y/m/d"),
			'user_url_image_alias' => "",
			'user_url_background' => "",
			'user_jog_present' => "",
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

	public function setCookieToAryAttachment($params) {
		$this->_HuyLibCookie = new HuyLib_Cookie();
		$this->_HuyLibCookie->setNewCookieForSendMailInFiveMinute($params);
	}
}