<?php
namespace packages\contactus\Listeners;
use \packages\notifications\Events;
use \packages\contactus\Events as ContactusEevents;
class Notifications{
	public function events(Events $events){
		$events->add(ContactusEevents\Letters\Receive::class);
		$events->add(ContactusEevents\Letters\Reply::class);
	}
}