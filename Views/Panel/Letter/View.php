<?php
namespace packages\contactus\Views\Panel\Letter;
use \packages\base\Translator;
use \packages\userpanel\Date;
use \packages\contactus\Views\Form;
use \packages\contactus\Letter;
class View extends Form{
	public function setLetter(Letter $letter){
		$this->setData($letter, "letter");
		$this->setDataForm($letter->toArray());
		$this->setDataForm(Translator::trans("letter.subject.reply", array('letter_subject' => $letter->subject)), "subject");
	}
	public function getLetter(){
		return $this->getData("letter");
	}
	public function setAddresses($emails){
		$this->setData($emails, "emails");
	}
	public function getAddresses(){
		return $this->getData("emails");
	}
}
