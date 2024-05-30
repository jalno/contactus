<?php
namespace packages\contactus\Views\Panel\Letter;
use \packages\contactus\Views\Form;
use \packages\contactus\Letter;
class Delete extends Form{
	public function setLetter(Letter $letter){
		$this->setData($letter, "letter");
	}
	public function getLetter(){
		return $this->getData("letter");
	}
}
