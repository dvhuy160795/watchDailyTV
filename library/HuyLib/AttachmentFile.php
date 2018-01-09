<?php
class AttachmentFile
{
    public function SaveAttachmentFile($aryFile) {
        //define 
        $upload= new Zend_File_Transfer();
        //create path dir if dir not exist
        if (!is_dir(APPLICATION_PATH."/temp/".$aryFile['dir'])) {
            mkdir(APPLICATION_PATH."/temp/".$aryFile['dir']);
        }
        //set path dir
        $upload->setDestination(APPLICATION_PATH."/temp/".$aryFile['dir']);

        //upload to path dir
        $upload->receive();
    }
    public function ViewAttachmentFile($aryListToAddress = [], $codeRandom) {
      
    }
}