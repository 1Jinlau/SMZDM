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

        printf("%s PushDeer 通知进行中...\n", $this->title);
        $message = new PushDeerMessage(PushDeerMessage::TYPE_TEXT, $this->message, $this->title);
        $channel->request($message);

        printf("%s PushDeer 通知推送[%s】\n", $this->title, $channel->getStatus() ? '成功' : '失败');
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

        printf("%s 钉钉群通知进行中...\n", $this->title);
        $message = new DingtalkMessage(DingtalkMessage::TYPE_TEXT, $this->message, $this->title);
        $message->setIsAll(true);
        $channel->request($message);

        printf("%s 钉钉群通知推送[%s】\n", $this->title, $channel->getStatus() ? '成功' : '失败');
        return $channel->getStatus();
    }
}
