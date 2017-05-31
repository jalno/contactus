<?php
namespace packages\contactus\listeners;
use \packages\notifications\events;
use \packages\contactus\events as contactusEevents;
class notifications{
	public function events(events $events){
		$events->add(contactusEevents\letters\receive::class);
	}
}