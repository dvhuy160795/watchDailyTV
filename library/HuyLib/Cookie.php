<?php
class HuyLib_Cookie extends Zend_Controller_Plugin_Abstract
{
    public function setCookieFiveMinute ($arySet = [], $arySetEncode = []) {
      if (!empty($arySet)) {
        foreach ($arySet as $key => $value) {
          if (!isset($_COOKIE[$key])) {
              setcookie($key,$value, time() + 300);
          }
        }
      }
      
      if (!empty($arySetEncode)) {
        foreach ($arySetEncode as $key => $value) {
          if (!isset($_COOKIE[$key])) {
              setcookie($key,hash("sha256",$value), time() + 300);
          }
        }
      }
    }

    public function sendMailRegisterUser($aryListToAddress = [], $codeRandom) {
      
    }
}
