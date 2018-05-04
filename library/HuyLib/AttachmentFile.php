<?php
class HuyLib_AttachmentFile extends Zend_Controller_Plugin_Abstract
{
    public function UploadAttachmentFile(&$arrayFile, $dir) {
        //define 
        $upload= new Zend_File_Transfer_Adapter_Http();
        $libDatabase = new HuyLib_DataBase();
        //create path dir if dir not exist
        if (is_dir(PUBLIC_PATH."/temp/".$dir) == false) {
            mkdir(PUBLIC_PATH."/temp/".$dir,0777);
        }
        $arrayFile = $upload->getFileInfo();
        $upload->setDestination(PUBLIC_PATH."/temp/".$dir);
        //upload to path dir
        $upload->receive();
        if (!empty($arrayFile)) {
            foreach ($arrayFile as $index => $file) {
                $randCode = rand(0, 999999);
                $strName = $this->renderNameAttachment($file['name']);
                $strExtendTion = $this->buildExtendTionString($file['type']);
                //set path dir
                rename(PUBLIC_PATH."/temp/".$dir."/".$file['name'],PUBLIC_PATH."/temp/".$dir."/".$libDatabase->buildCodeInsertByDateTime().$strName.$randCode.".".$strExtendTion);
                $arrayFile[$index]['name'] = $strName.".".$strExtendTion;
                $arrayFile[$index]['url_path'] = "/temp/".$dir."/".$libDatabase->buildCodeInsertByDateTime().$strName.$randCode.".".$strExtendTion;
            }
        }
        return $arrayFile;
    }
    public function ViewAttachmentFile($aryListToAddress = [], $codeRandom) {
      
    }
    
    public function getExtendtion() {
        return [
            'image/png' => "png",
            'image/jpeg' => "jpeg",
            'image/jpg' => "jpg",
            'image/gif' => "gif",
            'video/mp4' => "mp4",
            'video/x-flv' => "flv",
            'video/x-matroska' => "mkv",
            'application/octet-stream' => "3gp",
        ];
    }
    
    public function buildExtendTionString ($fileType) {
        $strExtendTion = "mp4";
        if (isset($fileType)) {
            $listExtendTion = $this->getExtendtion();
            $strExtendTion = $listExtendTion[$fileType];
        }
        return $strExtendTion;
    }

    public function renderNameAttachment($fileName) {
        $fileNameHashed = base64_encode($fileName);
        return $fileNameHashed;
    }
    
    
}