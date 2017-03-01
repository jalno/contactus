<?php
namespace packages\contactus\views\panel\letter;
use \packages\contactus\views\form;
use \packages\contactus\letter;
class delete extends form{
	public function setLetter(letter $letter){
		$this->setData($letter, "letter");
	}
	public function getLetter(){
		return $this->getData("letter");
	}
}
