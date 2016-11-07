<?php
namespace packages\contactus\views\panel;
use \packages\contactus\view;
use \packages\contactus\authorization;
use \packages\contactus\letter;
class delete extends view{
	protected $contactletter;
	public function setLetter(letter $letter){
		$this->contactletter = $letter;
	}
	public function getLetter(){
		return $this->contactletter;
	}
}
