<?php

use Pusher\Channel\Dingtalk;
use Pusher\Channel\PushDeer;
use Pusher\Message\DingtalkMessage;
use Pusher\Message\PushDeerMessage;

class Notify
{
    private string $message = '';
    private string $title = '';

    public function __construct(string $message, string $title = '')
    {
        $this->message = $message;
        $this->title = $title ?: 'check_in';
    }

    public function pusher(): bool
    {
        $token = getenv('PushDeerToken');
        if (! $token) {
            return false;
        }

        $channel = new PushDeer();
        $channel->setToken($token);

        $message = new PushDeerMessage(PushDeerMessage::TYPE_TEXT, $this->message, $this->title);
        $channel->request($message);
        return $channel->getStatus();
    }

    public function dingtalk(): bool
    {
        $token = getenv('DingtalkToken');
        $secret = getenv('DingtalkSecret');
        if (! $token) {
            return false;
        }

        $channel = new Dingtalk();
        $channel->setToken($token);

        if ($secret) {
            $channel->setSecret($secret);
        }

        $message = new DingtalkMessage(DingtalkMessage::TYPE_TEXT, $this->message, $this->title);
        $message->setIsAll(true);
        $channel->request($message);
        return $channel->getStatus();
    }
}

