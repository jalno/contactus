<?php

namespace packages\contactus;

use packages\base\DB\DBObject;
use packages\contactus\Letter\Reply;

class Letter extends DBObject
{
    public const unread = 1;
    public const read = 2;
    public const answered = 3;
    protected $dbTable = 'contactus_letter';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'date' => ['type' => 'int', 'required' => true],
        'ip' => ['type' => 'text', 'required' => true],
        'name' => ['type' => 'text', 'required' => true],
        'email' => ['type' => 'text', 'required' => true],
        'subject' => ['type' => 'text', 'required' => true],
        'text' => ['type' => 'text', 'required' => true],
        'status' => ['type' => 'int', 'required' => true],
    ];

    protected function preLoad($data)
    {
        if (!isset($data['date'])) {
            $data['date'] = time();
        }
        if (!isset($data['status'])) {
            $data['status'] = self::unread;
        }

        return $data;
    }

    public function getReply()
    {
        return Reply::where('letter', $this->id)->getOne();
    }
}
