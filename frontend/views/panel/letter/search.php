<?php
namespace themes\clipone\views\contactus\panel\letter;
use \packages\base\translator;

use \packages\userpanel;

use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\listTrait;
use \themes\clipone\views\formTrait;
use \themes\clipone\navigation\menuItem;

use \packages\contactus\letter;
use \packages\contactus\views\panel\letter\search as search_list;

class search extends search_list{
	use viewTrait, formTrait,listTrait;
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
	protected function getComparisonsForSelect(){
		return array(
			array(
				'title' => translator::trans('search.comparison.contains'),
				'value' => 'contains'
			),
			array(
				'title' => translator::trans('search.comparison.equals'),
				'value' => 'equals'
			),
			array(
				'title' => translator::trans('search.comparison.startswith'),
				'value' => 'startswith'
			)
		);
	}
	protected function getStatusForSelect(){
		return array(
			array(
				'title' => translator::trans('contactus.search.choose'),
				'value' => ''
			),
			array(
				'title' => translator::trans('contactus.letter.unread'),
				'value' => letter::unread
			),
			array(
				'title' => translator::trans('contactus.letter.read'),
				'value' => letter::read
			),
			array(
				'title' => translator::trans('contactus.letter.answered'),
				'value' => letter::answered
			)
		);
	}
}
