<?php
namespace themes\clipone\listeners\contactus;
use \packages\base;
use \packages\base\translator;
use \packages\userpanel;
use \packages\contactus\authorization;
use \themes\clipone\views\dashboard as view;
use \themes\clipone\views\dashboard\shortcut;
class dashboard{
	public function initialize(){
		if(authorization::is_accessed('letter_search')){
			$this->addShortcuts();
		}
	}
	protected function addShortcuts(){
		$shortcut = new shortcut("contactus");
		$shortcut->icon = 'fa fa-envelope';
		$shortcut->color = shortcut::teal;
		$shortcut->title = translator::trans('shortcut.contactus.title');
		$shortcut->text = translator::trans('shortcut.contactus.text');
		$shortcut->setLink(translator::trans('shortcut.contactus.link'), userpanel\url('contactus'));
		view::addShortcut($shortcut);
	}
}
