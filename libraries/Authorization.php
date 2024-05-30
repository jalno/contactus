<?php
namespace packages\contactus;
use \packages\userpanel\Authorization as UserPanelAuthorization;
class Authorization extends UserPanelAuthorization{
	static function is_accessed($permission, $prefix = 'contactus'){
		return parent::is_accessed($permission, $prefix);
	}
	static function haveOrFail($permission, $prefix = 'contactus'){
		parent::haveOrFail($permission, $prefix);
	}
}
