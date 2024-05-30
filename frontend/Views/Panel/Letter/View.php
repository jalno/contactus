<?php
namespace themes\clipone\Views\Contactus\Panel\Letter;
use \packages\base\Translator;
use \packages\base\Frontend\Theme;

use \packages\userpanel;
use \packages\userpanel\Date;

use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\FormTrait;

use \packages\contactus\Views\Panel\Letter\View as LetterView;

class View extends LetterView{
	use ViewTrait, FormTrait;
	protected $letter;
	function __beforeLoad(){
		$this->letter = $this->getLetter();
		$this->setTitle(array(
			Translator::trans('contactus'),
			$this->letter->subject
		));
		$this->addBodyClass('contactus_letter_view');
		$this->addAssets();
		Navigation::active("contactus");
	}
	private function addAssets(){
		if(!$this->letter->reply){
			$this->addJSFile(Theme::url('assets/plugins/ckeditor/ckeditor.js'));
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
		$date = Date::time()-$time;
		if($date == 0){
			$sent_at = Translator::trans("contactus.letter.replied.in.just.now");
		}elseif($date < 60){
			$sent_at = $date.Translator::trans("contactus.letter.replied.in.lastSec");
		}elseif($date >= 60 and $date < 3600){
			$sent_at = floor($date/60).Translator::trans("contactus.letter.replied.in.lastMin");
		}elseif($date >= 3600 and $date < 86400){
			$sent_at = floor($date/3600).Translator::trans("contactus.letter.replied.in.lastHov");
		}elseif($date >= 86400 and $date < 2592000){
			$sent_at = floor($date/86400).Translator::trans("contactus.letter.replied.in.lastDay");
		}elseif(2592000 >= 2592000 and $date < 31104000){
			$sent_at = floor($date/2592000).Translator::trans("contactus.letter.replied.in.lastMonth");
		}elseif($date >= 31104000){
			$sent_at = floor($date/31104000).Translator::trans("contactus.letter.replied.in.lastYear");
		}
		return $sent_at;
	}
}
