<?php
namespace themes\clipone\views\contactus\panel\letter;
use \packages\userpanel;
use \packages\base\translator;
use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\formTrait;
use \packages\contactus\views\panel\letter\delete as letter_delete;

class delete extends letter_delete{
	use viewTrait ,formTrait;
	protected $letter;
	function __beforeLoad(){
		$this->letter = $this->getLetter();
		$this->setTitle(array(
			translator::trans('contactus'),
			translator::trans('letter_delete')
		));
		navigation::active("contactus");
	}
}
