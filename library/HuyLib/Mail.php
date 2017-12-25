<?php
class HuyLib_Mail extends Zend_Controller_Plugin_Abstract
{
    public function send($arrayFrom, $arrayTo, $body, $subject) {
        $mail = new Zend_Mail();
    }
}
