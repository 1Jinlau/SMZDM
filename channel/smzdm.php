<?php

use Pusher\Channel\Webhook;
use Pusher\Message\WebhookMessage;
use Pusher\Pusher;

function smzdm(): array
{
    $url = 'https://zhiyou.smzdm.com/user/checkin/jsonp_checkin';

    $resp = [
        'title' => '什么值得买 签到',
        'reason' => '',
        'status' => false,
    ];

    $cookie = getenv('COOKIE_SMZDM');
    if (! $cookie) {
        $resp['reason'] = 'cookie 不存在';
        return $resp;
    }
    // $cookie = utf8_encode($cookie);

    $headers = [
        'Accept' => '*/*',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'zh-CN,zh;q=0.9',
        'Connection' => 'keep-alive',
        'Host' => 'zhiyou.smzdm.com',
        'Referer' => 'https://www.smzdm.com/',
        'Sec-Fetch-Dest' => 'script',
        'Sec-Fetch-Mode' => 'no-cors',
        'Sec-Fetch-Site' => 'same-site',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.131 Safari/537.36',
    ]; 
    $headers['Cookie'] = $cookie;

    $options = [
        'headers' => $headers,
    ];

    $channel = new Webhook();
    $channel->setReqURL($url)
        ->setMethod(Pusher::METHOD_GET)
        ->setOptions($options);

    $message = new WebhookMessage();

    $channel->request($message);
    $contents = $channel->getContents();
    $response = json_decode($contents, true);

    if ($response['error_code'] !== 0) {
        $resp['reason'] = $response['error_msg'];
        return $resp;
    }

    $data = $response['data'];
    $resp['reason'] = sprintf("\n⭐⭐⭐签到成功 %s 天⭐⭐⭐\n🏅🏅🏅金币[%d]\n🏅🏅🏅积分[%d]\n🏅🏅🏅经验[%d]\n🏅🏅🏅等级[%d]\n🏅🏅补签卡[%s]",
        $data['checkin_num'],
        $data['gold'],
        $data['point'],
        $data['exp'],
        $data['rank'],
        $data['cards'],
    );
    $resp['status'] = true;
    return $resp;
}
