<?php
class HuyLib_Cookie extends Zend_Controller_Plugin_Abstract
{
    public function setNewCookieForSendMailInFiveMinute($arySet = []) {
      foreach ($arySet as $key => $value) {
      	if (!isset($_COOKIE[$key])) {
      		if ($key == "user_code_register") {
      			setcookie($key,hash("sha256",$value), time() + 300);
      		} else {
      			setcookie($key,$value, time() + 300);
      		}
      	}
      }
    }
    public function sendMailRegisterUser($aryListToAddress = [], $codeRandom) {
      
    }
}
