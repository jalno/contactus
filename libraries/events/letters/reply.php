<?php
namespace packages\contactus\events\letters;
use \packages\base\event;
use \packages\userpanel\user;
use \packages\userpanel\usertype;
use \packages\notifications\notifiable;
use \packages\contactus\letter;
class reply extends event implements notifiable{
	private $reply;
	public function __construct(letter\reply $reply){
		$this->reply = $reply;
	}
	public function getReply():letter{
		return $this->reply;
	}
	public static function getName():string{
		return 'contactus_letter_reply';
	}
	public static function getParameters():array{
		return [letter::class];
	}
	public function getArguments():array{
		return [
			'reply' => $this->getReply()
		];
	}
	public function getTargetUsers():array{
		$users = [];
		$types = [];
		$permission = new usertype\permission();
		$permission->where("name", "contactus_letter_view");
		$permission->with('type');
		foreach($permission->get() as $permission){
			$types[] = $permission->type->id;
		}
		if($types){
			$users = user::where("type", $types,'in')->get();
		}
		return $users;
	}
}