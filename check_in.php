<?php

include_once 'vendor/autoload.php';
include_once 'notify.php';

include_once 'channel/smzdm.php';

function notify_message(array $resp): string
{
    return sprintf("【%s%s】\r\n%s", $resp['title'], $resp['status'] ? '成功' : '失败', $resp['reason']);
}

$list = [
    [
        'name' => 'smzdm',
        'title' => '【什么值得买】签到'
    ]
];

foreach ($list as $value) {
    $resp = '';
    $title = '';

    switch ($value['name']) {
        case 'smzdm':
            $title = $value['title'];
            $resp = notify_message(smzdm());
            break;
    }

    if ($resp !== '') {
        printf("%s 正在签到...\n", $title);
        $check_in = new Notify($resp, $title);
        
        $check_in->pusher();
        $check_in->dingtalk();
        $check_in->Pushplus();
    }
}
