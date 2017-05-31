<?php
namespace packages\contactus\events\letters;
use \packages\base\event;
use \packages\userpanel\user;
use \packages\userpanel\usertype;
use \packages\notifications\notifiable;
use \packages\contactus\letter;
class receive extends event implements notifiable{
	private $letter;
	public function __construct(letter $letter){
		$this->letter = $letter;
	}
	public function getLetter():letter{
		return $this->letter;
	}
	public static function getName():string{
		return 'contactus_letter_receive';
	}
	public static function getParameters():array{
		return [letter::class];
	}
	public function getArguments():array{
		return [
			'letter' => $this->getLetter()
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