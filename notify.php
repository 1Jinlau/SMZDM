<?php

use Pusher\Channel\Dingtalk;
use Pusher\Channel\PushDeer;
use Pusher\Channel\Pushplustalk;
use Pusher\Message\DingtalkMessage;
use Pusher\Message\PushDeerMessage;
use Pusher\Message\PUSHPLUSMessage;

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

    public function PUSHPLUS(): bool
    {
        $token = getenv('PUSHPLUSToken');
        if (! $token) {
            return false;
        }

        $channel = new PUSHPLUS();
        $channel->setToken($token);

        if ($secret) {
            $channel->setSecret($secret);
        }

        printf("%s PushDeer 通知进行中...\n", $this->title);
        $message = new PUSHPLUSMessage(PUSHPLUSMessage::TYPE_TEXT, $this->message, $this->title);
        $channel->request($message);

        printf("%s PUSHPLUS 通知推送[%s】\n", $this->title, $channel->getStatus() ? '成功' : '失败');
        return $channel->getStatus();
    }

