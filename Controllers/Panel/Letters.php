<?php
namespace packages\contactus\Controllers\Panel;
use \packages\base;
use \packages\base\DB;
use \packages\base\HTTP;
use \packages\base\Options;
use \packages\base\Packages;
use \packages\base\NotFound;
use \packages\base\DB\Parenthesis;
use \packages\base\Frontend\Theme;
use \packages\base\InputValidation;
use \packages\base\Views\FormError;
use \packages\base\View\Error;

use \packages\userpanel;
use \packages\userpanel\User;
use \packages\userpanel\Date;
use \packages\userpanel\View;
use \packages\userpanel\Controller;
use \packages\userpanel\Authentication;

use \packages\email\API;
use \packages\email\Sender;
use \packages\email\Sender\Address;

use \packages\contactus\Authorization;
use \packages\contactus\Letter;
use \packages\contactus\Letter\Reply;
use \packages\contactus\Events;
use packages\contactus\Views\Panel\Letter as LetterView;


class Letters extends Controller{
	protected $authentication = true;
	public function search(){
		Authorization::haveOrFail('letter_search');
		$view = View::byName(LetterView\Search::class);
		$letter = new Letter();
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
				'values' => array(Letter::unread, Letter::read, Letter::answered)
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
				$parenthesis = new Parenthesis();
				foreach(array('subject', 'text', 'name', 'email') as $item){
					if(!isset($inputs[$item]) or !$inputs[$item]){
						$parenthesis->where($item,$inputs['word'], $inputs['comparison'], 'OR');
					}
				}
				$letter->where($parenthesis);
			}
		}catch(InputValidation $error){
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
		Authorization::haveOrFail('letter_delete');
		$letter = Letter::byId($data['id']);
		if(!$letter){
			throw new NotFound;
		}
		$view = View::byName(LetterView\Delete::class);
		$view->setLetter($letter);
		$this->response->setStatus(false);
		if(HTTP::is_post()){
			try{
				$letter->delete();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url("contactus"));
			}catch(InputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}
		}else{
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
	public function view($data){
		Authorization::haveOrFail('letter_view');
		$letter = Letter::byId($data['letter']);
		if(!$letter){
			throw new NotFound;
		}
		$view = View::byName(LetterView\View::class);
		$view->setLetter($letter);
		DB::join("email_senders", "email_senders_addresses.sender=email_senders.id", "inner");
		DB::where("email_senders.status", Sender::active);
		DB::where("email_senders_addresses.status", Address::active);
		$addressesData = DB::get("email_senders_addresses", null, "email_senders_addresses.*");
		$addresses = array();
		$addressesID = array();
		foreach($addressesData as $data){
			$address = new Address($data);
			$addressesID[] = $address->id;
			$addresses[$address->id] = $address;
		}
		$view->setAddresses($addresses);
		if(HTTP::is_post()){
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
					throw new InputValidation("email");
				}
				if(isset($inputs['attachments'])){
					foreach($inputs['attachments'] as $key => $attachment){
						if($attachment['error'] == 4){
							unset($inputs['attachments'][$key]);
						}elseif(isset($attachment['error']) and $attachment['error'] != 0){
							throw new InputValidation("attachments[{$key}]");
						}
					}

				}
				$inputs['email'] = $addresses[$inputs['email']];
				
				$reply = new Reply;
				$reply->sender = Authentication::getID();
				$reply->letter = $letter->id;
				$reply->email = $inputs['email']->id;
				$reply->text = $inputs['html'];

				$emailApi = new API();
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
					'reply->letter->date' => Date::format('l d F Y', $letter->date),
				));
				if(!$emailApi->send()){
					throw new EmailNotSent();
				}
				
				$reply->save();

				$letter->reply = $reply->id;
				$letter->status = Letter::answered;
				$letter->save();
				$event = new Events\Letters\Reply($reply);
				$event->trigger();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url("contactus/view/".$letter->id));
			}catch(InputValidation $error){
				$view->setFormError(FormError::fromException($error));
			}catch(EmailNotSent $error){
				$error = new Error();
				$error->setCode("EmailNotSent");
				$view->addError($error);
			}
		}else{
			if($letter->status == Letter::unread){
				$letter->status = Letter::read;
				$letter->save();
			}
			$this->response->setStatus(true);
		}
		$this->response->setView($view);
		return $this->response;
	}
}
