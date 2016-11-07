<?php
namespace packages\contactus\controllers\panel;
use \packages\base;
use \packages\base\http;
use \packages\base\frontend\theme;
use \packages\base\NotFound;
use \packages\base\inputValidation;
use \packages\base\views\FormError;
use \packages\base\packages;


use \packages\userpanel\controller;
use \packages\userpanel;
use \packages\userpanel\user;
use \packages\userpanel\date;
use \packages\userpanel\view;

use \packages\contactus\contact_letter;
use \packages\contactus\authorization;


class homepage extends controller{
	protected $authentication = true;
	public function index(){
		authorization::haveOrFail('list');
		$view = view::byName("\\packages\\contactus\\views\\panel\\listview");
		$letters = new contact_letter();
		$letters->orderBy('date', 'DESC');
		$view->setLetters($letters->get());
		$this->response->setView($view);
		return $this->response;
	}
}
