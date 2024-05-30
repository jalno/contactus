<?php
namespace packages\contactus\Events\Letters;
use \packages\base\Event;
use \packages\userpanel\User;
use \packages\userpanel\UserType;
use \packages\notifications\Notifiable;
use \packages\contactus\Letter;
class Receive extends Event implements Notifiable{
	private $letter;
	public function __construct(Letter $letter){
		$this->letter = $letter;
	}
	public function getLetter():Letter{
		return $this->letter;
	}
	public static function getName():string{
		return 'contactus_letter_receive';
	}
	public static function getParameters():array{
		return [Letter::class];
	}
	public function getArguments():array{
		return [
			'letter' => $this->getLetter()
		];
	}
	public function getTargetUsers():array{
		$users = [];
		$types = [];
		$permission = new UserType\Permission();
		$permission->where("name", "contactus_letter_view");
		$permission->with('type');
		foreach($permission->get() as $permission){
			$types[] = $permission->type->id;
		}
		if($types){
			$users = User::where("type", $types,'in')->get();
		}
		return $users;
	}
}