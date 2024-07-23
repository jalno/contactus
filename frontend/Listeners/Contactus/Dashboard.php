<?php

namespace themes\clipone\Listeners\Contactus;

use packages\base\Translator;
use packages\contactus\Authorization;
use packages\contactus\Letter;
use packages\userpanel;
use themes\clipone\Views\Dashboard as View;
use themes\clipone\Views\Dashboard\Shortcut;

class Dashboard
{
    public function initialize()
    {
        if (Authorization::is_accessed('letter_search')) {
            $this->addShortcuts();
        }
    }

    protected function addShortcuts()
    {
        $letter = new Letter();
        $letter->where('status', Letter::answered, '!=');
        $letters = $letter->count();
        $shortcut = new Shortcut('contactus');
        $shortcut->icon = 'fa fa-envelope';
        if ($letters) {
            $shortcut->title = $letters;
            $shortcut->text = Translator::trans('shortcut.contactus.unread.letter');
            $shortcut->setLink(Translator::trans('shortcut.contactus.link'), userpanel\url('contactus'));
        } else {
            $shortcut->text = Translator::trans('shortcut.contactus.unread.letter.iszero');
        }
        View::addShortcut($shortcut);
    }
}