<?php
namespace packages\contactus;
use \packages\base\db;
use \packages\base\db\dbObject;

class letter extends dbObject{
	const unread = 1;
	const read = 2;
	const answered = 3;
	protected $dbTable = "contactus_letter";
	protected $primaryKey = "id";
	protected $dbFields = array(
		'reply' => array('type' => 'int'),
		'date' => array('type' => 'int', 'required' => true),
        'ip' => array('type' => 'text', 'required' => true),
        'name' => array('type' => 'text', 'required' => true),
        'email' => array('type' => 'text', 'required' => true),
        'subject' => array('type' => 'text', 'required' => true),
        'text' => array('type' => 'text', 'required' => true),
		'status' => array('type' => 'int', 'required' => true),
    );
	protected function preLoad($data){
		if(!isset($data['date'])){
			$data['date'] = time();
		}
		if(!isset($data['status'])){
			$data['status'] = self::unread;
		}
		return $data;
	}
	protected $relations = array(
		'reply' => array('hasOne', 'packages\\contactus\\letter\\reply', 'reply')
	);
}
