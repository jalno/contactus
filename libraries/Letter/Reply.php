<?php

namespace packages\contactus\Letter;

use packages\base\DB\DBObject;
use packages\userpanel\Date;
use packages\userpanel\User;
use packages\contactus\Letter;
use packages\email\Sender\Address;

class Reply extends DBObject
{
    public const unread = 1;
    public const read = 2;
    protected $dbTable = 'contactus_letter_reply';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'letter' => ['type' => 'int', 'required' => true],
        'date' => ['type' => 'int', 'required' => true],
        'sender' => ['type' => 'int', 'required' => true],
        'email' => ['type' => 'int', 'required' => true],
        'text' => ['type' => 'text', 'required' => true],
    ];
    protected $relations = [
        'sender' => ['hasOne', User::class, 'sender'],
        'letter' => ['hasOne', Letter::class, 'letter'],
        'email' => ['hasOne', Address::class, 'email'],
    ];

    protected function preLoad($data)
    {
        if (!isset($data['date'])) {
            $data['date'] = Date::time();
        }

        return $data;
    }
}
