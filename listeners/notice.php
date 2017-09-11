<?php
namespace packages\contactus\listeners;
use \packages\contactus\views;
use \packages\notice\events\views as event;
use \packages\notice\events\views\view;

class notice{
	public function views(event $event){
		$event->addView(new view(views\panel\letter\search::class));
		$event->addView(new view(views\panel\letter\view::class));
		$event->addView(new view(views\panel\letter\delete::class));
	}
}
