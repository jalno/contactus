<?php

namespace packages\contactus\Listeners;

use packages\base\DB\Parenthesis;
use packages\base\Translator;
use packages\contactus\Authorization;
use packages\contactus\Letter;
use packages\contactus\Letter\Reply;
use packages\email\Sender\Address;
use packages\userpanel;
use packages\userpanel\Date;
use packages\userpanel\Events\Search as Event;
use packages\userpanel\Search as SearchHandler;
use packages\userpanel\Search\Link;

class Search
{
    public function find(Event $e)
    {
        if (Authorization::is_accessed('letter_search')) {
            $this->letters($e->word);
            $this->letterReplies($e->word);
        }
    }

    public function letters($word)
    {
        $parenthesis = new Parenthesis();
        foreach (['ip', 'name', 'email', 'subject', 'text'] as $item) {
            $parenthesis->where($item, $word, 'contains', 'OR');
        }
        $letter = new Letter();
        $letter->where($parenthesis);
        $letters = $letter->get();
        foreach ($letters as $letter) {
            $result = new Link();
            $result->setLink(userpanel\url('contactus/view/'.$letter->id));
            $result->setTitle(Translator::trans('contactus.letter.bySenderNameAndEmail', [
                'senderName' => $letter->name,
                'senderEmail' => $letter->email,
            ]));
            $result->setDescription(Translator::trans('contactus.letter.description', [
                'receive_at' => Date::format('Y/m/d H:i:s', $letter->date),
                'text' => mb_substr($letter->text, 0, 70),
                'subject' => mb_substr($letter->subject, 0, 30),
            ]));
            SearchHandler::addResult($result);
        }
    }

    public function letterReplies($word)
    {
        $parenthesis = new Parenthesis();
        $emailAddress = new Address();
        $emailAddress->where('address', $word, 'contains');
        if ($address = $emailAddress->getOne()) {
            $parenthesis->where('email', $address->id, 'equals', 'OR');
        }
        $parenthesis->where('text', $word, 'contains', 'OR');
        $letterReply = new Reply();
        $letterReply->where($parenthesis);
        $letterReplies = $letterReply->get();
        foreach ($letterReplies as $reply) {
            $result = new Link();
            $result->setLink(userpanel\url('contactus/view/'.$reply->letter->id));
            $result->setTitle(Translator::trans('contactus.letter.reply', [
                'subject' => $reply->letter->subject,
            ]));
            $result->setDescription(Translator::trans('contactus.letter.replied.description', [
                'receive_at' => Date::format('Y/m/d H:i:s', $reply->letter->date),
                'text' => mb_substr($reply->letter->text, 0, 70),
                'subject' => mb_substr($reply->letter->subject, 0, 30),
            ]));
            SearchHandler::addResult($result);
        }
    }
}
