<?php

class Application_Model_Logic
{
    public function __construct(){
        
    }

	public function validateMail($email) {
		return preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $email);
//		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function createFileAttachment() {

	}

	// public function setParamToView ($aryParams) {
	// 	foreach ($aryParams as $key => $value) {
	// 		$this->view->$key = $value;
	// 	}
	// }

	public function getOneItemInArr ($listArr,$itemGet) {
    	$arrResult = [];
    	foreach ($listArr as $arr) {
    		array_push($arrResult, $arr[$itemGet]);
    	}
    	return $arrResult;
    }

    public function setParamToView($arrParams) {
        foreach ($arrParams as $key => $value) {
                $this->view->$key = $value;
        }
    }
    
    public function configPermissionAdmin() {
        return [
            2 => '<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables" onclick="Default.setSelectedItemMenuLeft(this)">
                    <a class="nav-link" href="tables.html">
                      <i class="fa fa-fw fa-table"></i>
                      <span class="nav-link-text">Videos</span>
                    </a>
                  </li>',
            3 => '<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Components" onclick="Admin.showUsersInListVideo();Default.setSelectedItemMenuLeft(this)">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseComponents" data-parent="#exampleAccordion">
                      <i class="fa fa-fw fa-wrench"></i>
                      <span class="nav-link-text">List Video</span>
                    </a>
                  </li>',
            4 => '<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Example Pages" onclick="Default.setSelectedItemMenuLeft(this)">
                    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#collapseExamplePages" data-parent="#exampleAccordion">
                      <i class="fa fa-fw fa-file"></i>
                      <span class="nav-link-text">Permission</span>
                    </a>
                    <ul class="sidenav-second-level collapse" id="collapseExamplePages">
                      <li>
                          <a onclick="Admin.showFormAddGroupPermission()">Create group permission</a>
                      </li>
                      <li>
                          <a onclick="Admin.loadListGroupPermission()">Show list permission</a>
                      </li>
                    </ul>
                  </li>',
            1 => '<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels" onclick="Default.setSelectedItemMenuLeft(this);Admin.loadListMember()">
                    <a class="nav-link nav-link-collapse collapsed">
                    <i class="fa fa-fw fa-sitemap"></i>
                    <span class="nav-link-text">List Member</span>
                  </a>
                </li>',
        ];
    }
}

