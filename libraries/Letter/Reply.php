<?php
namespace packages\contactus\Letter;
use \packages\base\DB;
use \packages\base\DB\DBObject;
use \packages\userpanel\Date;

class Reply extends DBObject{
	const unread = 1;
	const read = 2;
	protected $dbTable = "contactus_letter_reply";
	protected $primaryKey = "id";
	protected $dbFields = array(
		'letter' => array('type' => 'int', 'required' => true),
		'date' => array('type' => 'int', 'required' => true),
        'sender' => array('type' => 'int', 'required' => true),
        'email' => array('type' => 'int', 'required' => true),
        'text' => array('type' => 'text', 'required' => true)
    );
	protected $relations = array(
		'sender' => array('hasOne', \packages\userpanel\User::class, 'sender'),
		'letter' => array('hasOne', \packages\contactus\Letter::class, 'letter'),
		'email' => array('hasOne', \packages\email\Sender\Address::class, 'email')
	);
	protected function preLoad($data){
		if(!isset($data['date'])){
			$data['date'] = Date::time();
		}
		return $data;
	}
}
