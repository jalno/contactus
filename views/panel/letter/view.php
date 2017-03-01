<?php
namespace packages\contactus\views\panel\letter;
use \packages\base\translator;
use \packages\userpanel\date;
use \packages\contactus\views\form;
use \packages\contactus\letter;
class view extends form{
	public function setLetter(letter $letter){
		$this->setData($letter, "letter");
		$this->setDataForm($letter->toArray());
		$this->setDataForm(translator::trans("letter.subject.reply", array('letter_subject' => $letter->subject)), "subject");
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
