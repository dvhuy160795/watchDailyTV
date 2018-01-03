<?php

class Admin_MemberController extends Zend_Controller_Action
{

    public function init()
    {
    	 if ($_SESSION['Admin'] == []) {
            $r = new Zend_Controller_Action_Helper_Redirector;
            $r->gotoUrl('Admin/Member/login');
        }
    }

    public function indexAction()
    {
    	$this->_helper->layout->disableLayout();
        echo "member page";
    }

    public function loginAction()
    {
    	$this->_helper->layout->disableLayout();
    }

}