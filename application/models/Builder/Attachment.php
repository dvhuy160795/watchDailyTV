<?php

class Application_Model_Builder_Attachment
{
	protected $_HuyLibCookie;
	public function __construct() {
		$this->_HuyLibCookie = new HuyLib_Cookie();
	}
	public function buildDataBeforeInsertAttachment (&$aryData, $aryParams) {
		$aryMiss = [
			'attachment_name' => $aryData['attachment_file_name'],
			'attachment_type_upload' => $aryParams['controller']."-".$aryParams['action'],
			'created' => date("Y/m/d")
		];

		$aryData = array_merge($aryData,$aryMiss);
		return $intIsOk = 1;
	}

	public function setCookieToAryAttachment($params) {
		if (!empty($params)) {
			unset($params['avatarUser']['options']);
			unset($params['avatarUser']['validated']);
			unset($params['avatarUser']['received']);
			unset($params['avatarUser']['filtered']);
			unset($params['avatarUser']['validators']);
			if (!empty($params['avatarUser'])) {
				$params['avatarUser']['isAttachment'] = true;
			}
			$this->_HuyLibCookie->setCookieFiveMinute($params['avatarUser'],[]);
		}
	}
}