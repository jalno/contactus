<?php

namespace packages\contactus\Listeners;

use packages\contactus\Views;
use packages\notice\Events\Views as Event;
use packages\notice\Events\Views\View;

class Notice
{
    public function views(Event $event)
    {
        $event->addView(new View(Views\Panel\Letter\Search::class));
        $event->addView(new View(Views\Panel\Letter\View::class));
        $event->addView(new View(Views\Panel\Letter\Delete::class));
    }
}
