<?php
namespace themes\clipone\views\contactus\panel\letter;
use \packages\base\translator;
use \packages\base\frontend\theme;

use \packages\userpanel;
use \packages\userpanel\date;

use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\formTrait;

use \packages\contactus\views\panel\letter\view as letter_view;

class view extends letter_view{
	use viewTrait, formTrait;
	protected $letter;
	function __beforeLoad(){
		$this->letter = $this->getLetter();
		$this->setTitle(array(
			translator::trans('contactus'),
			$this->letter->subject
		));
		$this->addAssets();
		navigation::active("contactus");
	}
	private function addAssets(){
		if(!$this->letter->reply){
			$this->addJSFile(theme::url('assets/plugins/ckeditor/ckeditor.js'));
			$this->addCSSFile(theme::url('assets/css/pages/reply.css'));
		}
	}
	protected function getAddressesForSelect(){
		$addresses = array();
		foreach($this->getAddresses() as $address){
			$addresses[] = array(
				'title' => "{$address->name} <{$address->address}>",
				'value' => $address->id
			);
		}
		return $addresses;
	}
	protected function getDataTime($time){
		$date = date::time()-$time;
		if($date == 0){
			$sent_at = translator::trans("contactus.letter.replied.in.just.now");
		}elseif($date < 60){
			$sent_at = $date.translator::trans("contactus.letter.replied.in.lastSec");
		}elseif($date >= 60 and $date < 3600){
			$sent_at = floor($date/60).translator::trans("contactus.letter.replied.in.lastMin");
		}elseif($date >= 3600 and $date < 86400){
			$sent_at = floor($date/3600).translator::trans("contactus.letter.replied.in.lastHov");
		}elseif($date >= 86400 and $date < 2592000){
			$sent_at = floor($date/86400).translator::trans("contactus.letter.replied.in.lastDay");
		}elseif(2592000 >= 2592000 and $date < 31104000){
			$sent_at = floor($date/2592000).translator::trans("contactus.letter.replied.in.lastMonth");
		}elseif($date >= 31104000){
			$sent_at = floor($date/31104000).translator::trans("contactus.letter.replied.in.lastYear");
		}
		return $sent_at;
	}
}
