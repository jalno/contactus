<?php
namespace packages\contactus\listeners;
use \packages\email\template;
use \packages\email\events\templates;
class email{
	public function templates(templates $event){
		$event->addTemplate($this->replyLetterTemplate());
	}
	private function replyLetterTemplate(){
		$template = new template();
		$template->name = 'contactus_letter_reply';
		$template->addVariable('\\packages\\contactus\\letter\\reply');
		$template->addVariable('reply->letter->html');
		return $template;
	}
}
