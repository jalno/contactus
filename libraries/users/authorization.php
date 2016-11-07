<?php
namespace packages\contactus;
use \packages\userpanel\authorization as UserPanelAuthorization;
class authorization extends UserPanelAuthorization{
	static function is_accessed($permission, $prefix = 'contactus'){
		return parent::is_accessed($permission, $prefix);
	}
	static function haveOrFail($permission, $prefix = 'contactus'){
		parent::haveOrFail($permission, $prefix);
	}
}
