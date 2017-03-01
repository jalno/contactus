<?php
namespace packages\contactus\listeners;
use \packages\base\db;
use \packages\base\db\parenthesis;
use \packages\base\translator;

use \packages\userpanel;
use \packages\userpanel\date;
use \packages\userpanel\events\search as event;
use \packages\userpanel\search as saerchHandler;
use \packages\userpanel\search\link;

use \packages\email\sender\address;

use \packages\contactus\letter;
use \packages\contactus\letter\reply;
use \packages\contactus\authorization;
use \packages\contactus\authentication;

class search{
	public function find(event $e){
		if(authorization::is_accessed('letter_search')){
			$this->letters($e->word);
			$this->letterReplies($e->word);
		}
	}
	public function letters($word){
		$parenthesis = new parenthesis();
		foreach(array('ip', 'name', 'email', 'subject', 'text') as $item){
			$parenthesis->where($item, $word, 'contains', 'OR');
		}
		$letter = new letter();
		$letter->where($parenthesis);
		$letters = $letter->get();
		foreach($letters as $letter){
			$result = new link();
			$result->setLink(userpanel\url('contactus/view/'.$letter->id));
			$result->setTitle(translator::trans("contactus.letter.bySenderNameAndEmail", array(
				'senderName' => $letter->name,
				'senderEmail' => $letter->email
			)));
			$result->setDescription(translator::trans("contactus.letter.description", array(
				'receive_at' => date::format("Y/m/d H:i:s", $letter->date),
				'text' => mb_substr($letter->text, 0, 70),
				'subject' => mb_substr($letter->subject, 0, 30)
			)));
			saerchHandler::addResult($result);
		}
	}
	public function letterReplies($word){
		$parenthesis = new parenthesis();
		$emailAddress = new address();
		$emailAddress->where('address', $word, 'contains');
		if($address = $emailAddress->getOne()){
			$parenthesis->where('email', $address->id, 'equals', 'OR');
		}
		$parenthesis->where('text', $word, 'contains', 'OR');
		$letterReply = new reply();
		$letterReply->where($parenthesis);
		$letterReplies = $letterReply->get();
		foreach($letterReplies as $reply){
			$result = new link();
			$result->setLink(userpanel\url('contactus/view/'.$reply->letter->id));
			$result->setTitle(translator::trans("contactus.letter.reply", array(
				'subject' => $reply->letter->subject
			)));
			$result->setDescription(translator::trans("contactus.letter.replied.description", array(
				'receive_at' => date::format("Y/m/d H:i:s", $reply->letter->date),
				'text' => mb_substr($reply->letter->text, 0, 70),
				'subject' => mb_substr($reply->letter->subject, 0, 30)
			)));
			saerchHandler::addResult($result);
		}
	}
}
