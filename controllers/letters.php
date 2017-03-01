<?php
namespace packages\contactus\controllers\panel;
use \packages\base;
use \packages\base\db;
use \packages\base\http;
use \packages\base\options;
use \packages\base\packages;
use \packages\base\NotFound;
use \packages\base\db\parenthesis;
use \packages\base\frontend\theme;
use \packages\base\inputValidation;
use \packages\base\views\FormError;

use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;
use \packages\userpanel\view;
use \packages\userpanel\controller;
use \packages\userpanel\authentication;

use \packages\email\api;
use \packages\email\sender;
use \packages\email\sender\address;

use \packages\contactus\authorization;
use \packages\contactus\letter;
use \packages\contactus\letter\reply;

class letters extends controller{
	protected $authentication = true;
	public function search(){
		authorization::haveOrFail('letter_search');
		$view = view::byName("\\packages\\contactus\\views\\panel\\letter\\search");
		$letter = new letter();
		$letter->orderBy('date', 'DESC');
		$inputsRules = array(
			'id' => array(
				'type' => 'number',
				'optional' => true,
				'empty' => true
			),
			'name' => array(
				'type' => 'string',
				'optional' => true,
				'empty' => true
			),
			'email' => array(
				'type' => 'string',
				'optional' => true,
				'empty' => true
			),
			'status' => array(
				'type' => 'number',
				'optional' =>true,
				'empty' => true,
				'values' => array(letter::unread, letter::read, letter::answered)
			),
			'word' => array(
				'type' => 'string',
				'optional' => true,
				'empty' => true
			),
			'comparison' => array(
				'values' => array('equals', 'startswith', 'contains'),
				'default' => 'contains',
				'optional' => true
			)
		);
		try{
			$inputs = $this->checkinputs($inputsRules);
			foreach(array('id', 'name', 'email', 'status') as $item){
				if(isset($inputs[$item]) and $inputs[$item]){
					$comparison = $inputs['comparison'];
					if(in_array($item, array('id', 'status'))){
						$comparison = 'equals';
					}
					$letter->where($item, $inputs[$item], $comparison);
				}
			}
			if(isset($inputs['word']) and $inputs['word']){
				$parenthesis = new parenthesis();
				foreach(array('subject', 'text', 'name', 'email') as $item){
					if(!isset($inputs[$item]) or !$inputs[$item]){
						$parenthesis->where($item,$inputs['word'], $inputs['comparison'], 'OR');
					}
				}
				$letter->where($parenthesis);
			}
		}catch(inputValidation $error){
			$view->setFormError(FormError::fromException($error));
		}
		$view->setDataForm($this->inputsvalue($inputs));
		$letter->pageLimit = $this->items_per_page;
		$letters = $letter->paginate($this->page);
		$view->setDataList($letters);
		$view->setPaginate($this->page, $letter->totalCount, $this->items_per_page);
		$this->response->setView($view);
		return $this->response;
	}
	public function delete($data){
		authorization::haveOrFail('letter_delete');
		$letter = letter::byId($data['id']);
		if(!$letter){
			throw new NotFound;
		}
		$view = view::byName("\\packages\\contactus\\views\\panel\\letter\\delete");
		$view->setLetter($letter);
		$this->response->setStatus(false);
		if(http::is_post()){
			try{
				$letter->delete();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url("contactus"));
			}catch(inputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}
		}else{
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
	public function view($data){
		authorization::haveOrFail('letter_view');
		$letter = letter::byId($data['letter']);
		if(!$letter){
			throw new NotFound;
		}
		$view = view::byName("\\packages\\contactus\\views\\panel\\letter\\view");
		$view->setLetter($letter);
		db::join("email_senders", "email_senders_addresses.sender=email_senders.id", "inner");
		db::where("email_senders.status", sender::active);
		db::where("email_senders_addresses.status", address::active);
		$addressesData = db::get("email_senders_addresses", null, "email_senders_addresses.*");
		$addresses = array();
		$addressesID = array();
		foreach($addressesData as $data){
			$address = new address($data);
			$addressesID[] = $address->id;
			$addresses[$address->id] = $address;
		}
		$view->setAddresses($addresses);
		if(http::is_post()){
			$this->response->setStatus(false);
			$inputsRules = array(
				'html' => array(
				),
				'subject' => array(
					'type' => 'string'
				),
				'email' => array(
					'type' => 'number'
				),
				'attachments' => array(
					'type' => 'file',
					'optional' => true,
					'empty' => true
				)
			);
			try{
				if($letter->reply){
					throw new NotFound;
				}
				$inputs = $this->checkinputs($inputsRules);
				if(!in_array($inputs['email'], $addressesID)){
					throw new inputValidation("email");
				}
				if(isset($inputs['attachments'])){
					foreach($inputs['attachments'] as $key => $attachment){
						if($attachment['error'] == 4){
							unset($inputs['attachments'][$key]);
						}elseif(isset($attachment['error']) and $attachment['error'] != 0){
							throw new inputValidation("attachments[{$key}]");
						}
					}

				}
				$inputs['email'] = $addresses[$inputs['email']];
				
				$reply = new reply;
				$reply->sender = authentication::getID();
				$reply->letter = $letter->id;
				$reply->email = $inputs['email']->id;
				$reply->text = $inputs['html'];

				$emailApi = new api();
				$emailApi->to($letter->email, $letter->name);
				$emailApi->fromAddress($inputs['email']);
				if(isset($inputs['attachments'])){
					foreach($inputs['attachments'] as $attachment){
						$emailApi->addAttachment($attachment['tmp_name'], $attachment['name']);
					}
				}
				$emailApi->now();
				$emailApi->template('contactus_letter_reply', array(
					'reply' => $reply,
					'reply->letter->html' => nl2br($letter->text),
					'reply->letter->date' => date::format('l d F Y', $letter->date),
				));
				if(!$emailApi->send()){
					throw new EmailNotSent();
				}
				
				$reply->save();

				$letter->reply = $reply->id;
				$letter->status = letter::answered;
				$letter->save();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url("contactus/view/".$letter->id));
			}catch(inputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}catch(EmailNotSent $error){
				$error = new error();
				$error->setCode("EmailNotSent");
				$view->addError($error);
			}
		}else{
			if($letter->status == letter::unread){
				$letter->status = letter::read;
				$letter->save();
			}
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
}
class EmailNotSent extends \Exception{}
