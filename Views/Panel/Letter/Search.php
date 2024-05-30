<?php
namespace packages\contactus\Views\Panel\Letter;
use \packages\contactus\Views\ListView;
use \packages\contactus\Authorization;
use \packages\base\Views\Traits\Form as FormTrait;
class Search extends ListView{
	use FormTrait;
	protected $canView;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canView = Authorization::is_accessed('letter_view');
		$this->canDel = Authorization::is_accessed('letter_delete');
	}
	public function getLetters(){
		return $this->dataList;
	}
	public static function onSourceLoad(){
		self::$navigation = Authorization::is_accessed('letter_search');
	}
}
