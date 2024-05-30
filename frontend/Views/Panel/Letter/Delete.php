<?php
namespace themes\clipone\Views\Contactus\Panel\Letter;
use \packages\userpanel;
use \packages\base\Translator;
use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\FormTrait;
use \packages\contactus\Views\Panel\Letter\Delete as LetterDelete;

class Delete extends LetterDelete{
	use ViewTrait,FormTrait;
	protected $letter;
	function __beforeLoad(){
		$this->letter = $this->getLetter();
		$this->setTitle(array(
			Translator::trans('contactus'),
			Translator::trans('letter_delete')
		));
		Navigation::active("contactus");
	}
}
