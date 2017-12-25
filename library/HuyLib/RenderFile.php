<?php
class HuyLib_RenderFile extends Zend_Controller_Plugin_Abstract
{
    public function renderCss($module, $controller, $action) {
        echo "<link rel='stylesheet' href='".$module."/".$controller."/".$action."style.css') >";
    }
}
