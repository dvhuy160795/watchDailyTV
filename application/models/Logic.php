<?php

class Application_Model_Logic extends Application_Model_DbTable_User
{
	public function buildDataInsertUpdate (&$arrData) {

		return $intIsOk;
	}

	public function validateMail($email) {
		return preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $email)
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function createFileAttachment() {

	}

}
