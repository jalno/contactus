<?php
namespace themes\clipone\listeners\contactus;
use packages\base\translator;
use packages\userpanel;
use packages\contactus\{authorization, letter};
use themes\clipone\views\dashboard as view;
use themes\clipone\views\dashboard\shortcut;
class dashboard{
	public function initialize(){
		if(authorization::is_accessed("letter_search")){
			$this->addShortcuts();
		}
	}
	protected function addShortcuts(){
		$letter = new letter();
		$letter->where("status", letter::answered, "!=");
		$letters = $letter->count();
		$shortcut = new shortcut("contactus");
		$shortcut->icon = "fa fa-envelope";
		if ($letters) {
			$shortcut->title = $letters;
			$shortcut->text = translator::trans("shortcut.contactus.unread.letter");
			$shortcut->setLink(translator::trans("shortcut.contactus.link"), userpanel\url("contactus"));
		} else {
			$shortcut->text = translator::trans("shortcut.contactus.unread.letter.iszero");
		}
		view::addShortcut($shortcut);
	}
}
