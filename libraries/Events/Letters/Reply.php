<?php

namespace packages\contactus\Events\Letters;

use packages\base\Event;
use packages\contactus\Letter;
use packages\notifications\Notifiable;
use packages\userpanel\User;
use packages\userpanel\UserType;

class Reply extends Event implements Notifiable
{
    private $reply;

    public function __construct(Letter\Reply $reply)
    {
        $this->reply = $reply;
    }

    public function getReply(): Letter\Reply
    {
        return $this->reply;
    }

    public static function getName(): string
    {
        return 'contactus_letter_reply';
    }

    public static function getParameters(): array
    {
        return [Letter::class];
    }

    public function getArguments(): array
    {
        return [
            'reply' => $this->getReply(),
        ];
    }

    public function getTargetUsers(): array
    {
        $users = [];
        $types = [];
        $permission = new UserType\Permission();
        $permission->where('name', 'contactus_letter_view');
        $permission->with('type');
        foreach ($permission->get() as $permission) {
            $types[] = $permission->type->id;
        }
        if ($types) {
            $users = User::where('type', $types, 'in')->get();
        }

        return $users;
    }
}
