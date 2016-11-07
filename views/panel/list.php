<?php
namespace packages\contactus\views\panel;
use \packages\contactus\view;
use \packages\contactus\authorization;
class listview extends view{
	protected $canView;
	protected $canDel;
	protected $contactletters;
	static protected $navigation;
	function __construct(){
		$this->canView = authorization::is_accessed('view');
		$this->canDel = authorization::is_accessed('delete');
	}

	public function setLetters($letters){
		$this->contactletters = $letters;
	}
	public function getLetters(){
		return $this->contactletters;
	}

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
