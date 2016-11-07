<?php
namespace themes\clipone\views\contactus\panel;
use \packages\base\translator;
use \packages\base\frontend\theme;

use \packages\userpanel;

use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\listTrait;
use \themes\clipone\views\formTrait;
use \themes\clipone\navigation\menuItem;

use \packages\contactus\views\panel\view as letter_view;

class view extends letter_view{
	use viewTrait ,listTrait, formTrait;
	function __beforeLoad(){
		$this->setTitle(array(
			translator::trans('contactus'),
			translator::trans('view')
		));
		$this->addAssets();
		navigation::active("contactus");
	}
	private function addAssets(){
		$this->addCSSFile(theme::url('assets/css/custom.css'));
	}
	protected function getEmailsValues(){
		$emails = array();
		foreach($this->getEmails() as $email){
			$emails[] = array(
				'title' => $email->address,
				'value' => $email->id
			);
		}
		return $emails;
	}
	protected function getDataTime($time){
		$date = time()-$time;
		if($date == 0){
			$lasTime = translator::trans("just.now");
		}elseif($date < 60){
			$lasTime = $date.translator::trans("lastSec");
		}elseif($date >= 60 and $date < 3600){
			$lasTime = floor($date/60).translator::trans("lastMin");
		}elseif($date >= 3600 and $date < 86400){
			$lasTime = floor($date/3600).translator::trans("lastHov");
		}elseif($date >= 86400 and $date < 2592000){
			$lasTime = floor($date/86400).translator::trans("lastDay");
		}elseif($date >= 31104000){
			$lasTime = floor($date/31104000).translator::trans("lastYear");
		}
		return $lasTime;
	}
}
