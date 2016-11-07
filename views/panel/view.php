<?php
namespace packages\contactus\views\panel;
use \packages\contactus\views\form;
use \packages\contactus\authorization;
use \packages\contactus\letter;
class view extends form{
	protected $contactletter;
	protected $emails;
	public function setLetter(letter $letter){
		$this->contactletter = $letter;
	}
	public function getLetter(){
		return $this->contactletter;
	}
	public function setEmails($emails){
		$this->emails = $emails;
	}
	public function getEmails(){
		return $this->emails;
	}
}
