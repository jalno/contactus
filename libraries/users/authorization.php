<?php
namespace packages\contactus;
use \packages\userpanel\authorization as UserPanelAuthorization;
use \packages\userpanel\authentication;
class authorization extends UserPanelAuthorization{
	static function is_accessed($permission){
		return authentication::getUser()->can("package_name_".$permission);
	}
}
