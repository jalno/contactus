<?php
namespace packages\contactus\letter;
use \packages\base\db;
use \packages\base\db\dbObject;
use \packages\userpanel\date;

class reply extends dbObject{
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
		'sender' => array('hasOne', 'packages\\userpanel\\user', 'sender'),
		'letter' => array('hasOne', 'packages\\contactus\\letter', 'letter'),
		'email' => array('hasOne', 'packages\\email\\sender\\address', 'email')
	);
	protected function preLoad($data){
		if(!isset($data['date'])){
			$data['date'] = date::time();
		}
		return $data;
	}
}
