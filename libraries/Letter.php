<?php
namespace packages\contactus;
use \packages\base\DB;
use \packages\base\DB\DBObject;
use \packages\contactus\Letter\Reply;

class Letter extends DBObject{
	const unread = 1;
	const read = 2;
	const answered = 3;
	protected $dbTable = "contactus_letter";
	protected $primaryKey = "id";
	protected $dbFields = array(
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
	public function getReply(){
		return Reply::where("letter", $this->id)->getOne();
	}
}
