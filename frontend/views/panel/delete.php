<?php
namespace themes\clipone\views\contactus\panel;
use \packages\contactus\views\panel\delete as letter_delete;
use \packages\userpanel;
use \themes\clipone\navigation;
use \themes\clipone\navigation\menuItem;
use \themes\clipone\views\listTrait;
use \themes\clipone\viewTrait;
use \packages\base\translator;

class delete extends letter_delete{
	use viewTrait ,listTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('contactus'),
			translator::trans('delete')
		));
		navigation::active("contactus");
	}
}
