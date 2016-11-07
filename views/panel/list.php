<?php
namespace packages\contactus\views\panel;
use \packages\contactus\views\listview as list_view;
use \packages\contactus\authorization;
class listview extends list_view{
	protected $canView;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canView = authorization::is_accessed('view');
		$this->canDel = authorization::is_accessed('delete');
	}

	public function getLetters(){
		return $this->dataList;
	}

	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('list');
	}
}
