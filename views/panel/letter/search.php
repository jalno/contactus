<?php
namespace packages\contactus\views\panel\letter;
use \packages\contactus\views\listview as list_view;
use \packages\contactus\authorization;
use \packages\base\views\traits\form as formTrait;
class search extends list_view{
	use formTrait;
	protected $canView;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canView = authorization::is_accessed('letter_view');
		$this->canDel = authorization::is_accessed('letter_delete');
	}
	public function getLetters(){
		return $this->dataList;
	}
	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('letter_search');
	}
}
