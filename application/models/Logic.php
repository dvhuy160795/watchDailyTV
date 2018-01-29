<?php

class Application_Model_Logic extends Application_Model_DbTable_User
{
    public function __construct(){
        
    }

	public function validateMail($email) {
		return preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $email);
//		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function createFileAttachment() {

	}

	public function setParamToView ($aryParams) {
		foreach ($aryParams as $key => $value) {
			$this->view->$key = $value;
		}
	}

	public function getOneItemInArr ($listArr,$itemGet) {
    	$arrResult = [];
    	foreach ($listArr as $arr) {
    		array_push($arrResult, $arr[$itemGet]);
    	}
    	return $arrResult;
    }

}

