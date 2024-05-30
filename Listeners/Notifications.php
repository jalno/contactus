<?php

namespace packages\contactus\Listeners;

use packages\contactus\Events as ContactusEevents;
use packages\notifications\Events;

class Notifications
{
    public function events(Events $events)
    {
        $events->add(ContactusEevents\Letters\Receive::class);
        $events->add(ContactusEevents\Letters\Reply::class);
    }
}
