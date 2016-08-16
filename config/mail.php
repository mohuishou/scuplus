<?php


return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.exmail.qq.com'),
    'port' => env('MAIL_PORT',465),
    'from' => [
        'address' =>env('MAIL_ADDRESS',''),
        'name' => env('MAIL_NAME','')
    ],
    'encryption' => env('MAIL_ENCRYPTION',''),
    'username' => env('MAIL_USERNAME',''),
    'password' => env('MAIL_PASSWORD',''),
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
];