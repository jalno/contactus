<?php
namespace packages\contactus\listeners\settings;
use \packages\userpanel\usertype\permissions;
class usertype{
	public function permissions_list(){
		$permissions = array(
			'letter_search',
			'letter_delete',
			'letter_view'

		);
		foreach($permissions as $permission){
			permissions::add('contactus_'.$permission);
		}
	}
}
