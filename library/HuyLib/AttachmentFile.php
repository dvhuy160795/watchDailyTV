<?php
class HuyLib_AttachmentFile extends Zend_Controller_Plugin_Abstract
{
    public function SaveAttachmentFile($dir) {
        //define 
        $upload= new Zend_File_Transfer();
        //create path dir if dir not exist
        if (is_dir(APPLICATION_PATH."/temp/".$dir) == false) {
            mkdir(APPLICATION_PATH."/temp/".$dir,0777);
        }
        //set path dir
        $upload->setDestination(APPLICATION_PATH."/temp/".$dir);

        //upload to path dir
        $upload->receive();
    }
    public function ViewAttachmentFile($aryListToAddress = [], $codeRandom) {
      
    }
}