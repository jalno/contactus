<?php
namespace packages\contactus\Listeners;
use \packages\email\Template;
use \packages\email\Events\Templates;
class Email{
	public function templates(Templates $event){
		$event->addTemplate($this->replyLetterTemplate());
	}
	private function replyLetterTemplate(){
		$template = new Template();
		$template->name = 'contactus_letter_reply';
		$template->addVariable(\packages\contactus\Letter\Reply::class);
		$template->addVariable('reply->letter->html');
		return $template;
	}
}
