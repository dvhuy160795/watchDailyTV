<?php
class HuyLib_DataBase extends Zend_Controller_Plugin_Abstract
{
    public function buildCodeInsertByDateTime() {
    	$code = date("Y").date("m").date("d").date("H").date("i");
      return $code;
    }
}
