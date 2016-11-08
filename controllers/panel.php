<?php
namespace packages\contactus\controllers\panel;
use \packages\base;
use \packages\base\http;
use \packages\base\options;
use \packages\base\packages;
use \packages\base\NotFound;
use \packages\base\frontend\theme;
use \packages\base\inputValidation;
use \packages\base\views\FormError;

use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;
use \packages\userpanel\view;
use \packages\userpanel\controller;
use \packages\userpanel\authentication;

use \packages\contactus\authorization;
use \packages\contactus\letter;
use \packages\contactus\letter\reply;

use \packages\notification\EMAIL;
use \packages\notification\emailAddress;


class homepage extends controller{
	protected $authentication = true;
	public function index(){
		authorization::haveOrFail('list');
		$view = view::byName("\\packages\\contactus\\views\\panel\\listview");
		$letter = new letter();
		$letter->orderBy('date', 'DESC');
		$letter->pageLimit = $this->items_per_page;
		$letters = $letter->paginate($this->page);
		$view->setDataList($letters);
		$view->setPaginate($this->page, $letter->totalCount, $this->items_per_page);
		$this->response->setView($view);
		return $this->response;
	}
	public function delete($data){
		authorization::haveOrFail('delete');
		$view = view::byName("\\packages\\contactus\\views\\panel\\delete");
		$letter = letter::byId($data['id']);
		if(!$letter){
			throw new NotFound;
		}
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
		authorization::haveOrFail('view');
		$view = view::byName("\\packages\\contactus\\views\\panel\\view");
		$letter = letter::byId($data['id']);
		if(!$letter){
			throw new NotFound;
		}
		$view->setLetter($letter);
		$view->setEmails(emailAddress::get());
		$inputsRules = array(
			'text' => array(
				'type' => 'string'
			),
			'subject' => array(
				'type' => 'string'
			),
			'email' => array(
				'type' => 'number'
			)
		);
		$this->response->setStatus(false);
		if(http::is_post()){
			try{
				if($letter->reply){
					throw new NotFound;
				}
				$inputs = $this->checkinputs($inputsRules);

				$inputs['email'] = emailAddress::byId($inputs['email']);
				if(!$inputs['email']){
					throw new inputValidation("email");
				}
				$reply_text = file_get_contents(__DIR__.'/../storage/private/reply-body-text.txt');
				$reply_text = str_replace("%TITLE%", options::get('theme.rocket.company'), $reply_text);
				$reply_text = str_replace("%SUBJECT%", $inputs['subject'], $reply_text);
				$reply_text = str_replace("%TEXT%", nl2br($inputs['text']), $reply_text);
				$reply_text = str_replace("%signing%", options::get("siging.email"), $reply_text);
				$user = user::byId(authentication::getID());
				$fullname = $user->name." ".$user->lastname;
				$result = EMAIL::send($user->id, $inputs['subject'], $reply_text, $letter->email, $fullname, $inputs['email']->address);
				$reply = new reply;
				$reply->sender = $user->id;
				$reply->email = $inputs['email']->id;
				$reply->text = $inputs['text'];
				$reply->save();

				$letter->reply = $reply->id;
				$letter->status = letter::answered;
				$letter->save();
				$this->response->setStatus(true);
				$this->response->Go(userpanel\url("contactus/view/".$letter->id));
			}catch(inputValidation $error){
				$view->setFormError(FormError::fromException($error));
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
