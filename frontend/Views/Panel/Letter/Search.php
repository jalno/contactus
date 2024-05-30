<?php
namespace themes\clipone\Views\Contactus\Panel\Letter;
use \packages\base\Translator;
use \packages\base\View\Error;
use \packages\userpanel;
use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\ListTrait;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\Navigation\MenuItem;
use \packages\contactus\Letter;
use \packages\contactus\Views\Panel\Letter\Search as SearchList;

class Search extends SearchList{
	use ViewTrait, FormTrait,ListTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			Translator::trans('contactus'),
			Translator::trans('list')
		));
		Navigation::active("contactus");
		$this->setButtons();
		if(empty($this->getLetters())){
			$this->addNotFoundError();
		}
	}
	private function addNotFoundError(){
		$error = new Error();
		$error->setType(Error::NOTICE);
		$error->setCode('contactus.letter.notfound');
		$this->addError($error);
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(self::$navigation){
			$item = new MenuItem("contactus");
			$item->setTitle(Translator::trans("contactus"));
			$item->setURL(userpanel\url('contactus'));
			$item->setIcon('fa fa-envelope');
			Navigation::addItem($item);
		}
	}
	public function setButtons(){
		$this->setButton('view', $this->canView, array(
			'title' => Translator::trans('view'),
			'icon' => 'fa fa-files-o',
			'classes' => array('btn', 'btn-xs', 'btn-green')
		));
		$this->setButton('delete', $this->canDel, array(
			'title' => Translator::trans('delete'),
			'icon' => 'fa fa-times',
			'classes' => array('btn', 'btn-xs', 'btn-bricky')
		));
	}
	protected function getComparisonsForSelect(){
		return array(
			array(
				'title' => Translator::trans('search.comparison.contains'),
				'value' => 'contains'
			),
			array(
				'title' => Translator::trans('search.comparison.equals'),
				'value' => 'equals'
			),
			array(
				'title' => Translator::trans('search.comparison.startswith'),
				'value' => 'startswith'
			)
		);
	}
	protected function getStatusForSelect(){
		return array(
			array(
				'title' => Translator::trans('contactus.search.choose'),
				'value' => ''
			),
			array(
				'title' => Translator::trans('contactus.letter.unread'),
				'value' => Letter::unread
			),
			array(
				'title' => Translator::trans('contactus.letter.read'),
				'value' => Letter::read
			),
			array(
				'title' => Translator::trans('contactus.letter.answered'),
				'value' => Letter::answered
			)
		);
	}
}
