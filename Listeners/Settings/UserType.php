<?php
namespace packages\contactus\Listeners\Settings;
use \packages\userpanel\UserType\Permissions;
class UserType{
	public function permissions_list(){
		$permissions = array(
			'letter_search',
			'letter_delete',
			'letter_view'

		);
		foreach($permissions as $permission){
			Permissions::add('contactus_'.$permission);
		}
	}
}
