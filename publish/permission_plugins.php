<?php

declare(strict_types=1);

return [
    'HOST' => env('PERMISSION_HOST', ''),
    'SECRET_ID' => env('PERMISSION_SECRET_ID', ''),
    'SECRET_KEY' => env('PERMISSION_SECRET_KEY', ''),
    'API' => [
        'ROUTES' => [
            'METHOD' => 'GET',
            'API' => '/api/v1/user/routes',
            'DESC' => '获取当前登录用户所有被允许访问的路由(后端使用,调用是强制Cookie写入TGC)',
        ],
    ],
];
