<?php
namespace themes\clipone\views\contactus\panel;
use \packages\contactus\views\panel\listview as list_view;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\listTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class listview extends list_view{
	use viewTrait ,listTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('contactus'),
			translator::trans('list')
		));
		navigation::active("contactus");
		$this->setButtons();
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(self::$navigation){
			$item = new menuItem("contactus");
			$item->setTitle(translator::trans("contactus"));
			$item->setURL(userpanel\url('contactus'));
			$item->setIcon('fa fa-envelope');
			navigation::addItem($item);
		}
	}
	public function setButtons(){
		$this->setButton('view', $this->canView, array(
			'title' => translator::trans('view'),
			'icon' => 'fa fa-files-o',
			'classes' => array('btn', 'btn-xs', 'btn-green')
		));
		$this->setButton('delete', $this->canDel, array(
			'title' => translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => array('btn', 'btn-xs', 'btn-bricky')
		));
	}
}
