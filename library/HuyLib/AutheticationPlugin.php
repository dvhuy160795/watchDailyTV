<?php
class HuyLib_AutheticationPlugin extends Zend_Controller_Plugin_Abstract
{
    protected $_params = [];
    protected $_AuthAdmin = [];


    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {

    }
 
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $this->_AuthAdmin = isset($_SESSION['Admin'])? $_SESSION['Admin']:[];

        $this->_params = $this->getRequest()->getParams();
        $_SESSION['paramUrl'] = $this->getRequest()->getParams();
        if ($this->_params['module'] == "Admin" && $this->_AuthAdmin == []) {
            echo "vui logn dang nhap";
        }
    }
 
    public function dispatchLoopStartup(
        Zend_Controller_Request_Abstract $request)
    {
        
    }
 
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

    }
 
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {

    }
 
    public function dispatchLoopShutdown()
    {

    }
}

