ezijing-permission-plugins
==================================

使用说明
----------------------------------

### 1、下载composer包
```shell
> composer require ezijing/ezijing-permission-plugins -vvv
```

### 2、发布配置
```shell
>  php bin/hyperf.php vendor:publish ezijing/ezijing-permission-plugins
```

### 3、更改配置文件
```PHP
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
```

#### 在项目根目录霞配置`.env`文件
```dotenv
PERMISSION_HOST=https://permissions-api.ezijing.com
PERMISSION_SECRET_ID=XXXX
PERMISSION_SECRET_KEY=XXXX
```

### 4、通过注解方式使用
```php
<?php

declare(strict_types=1);

namespace App\Controller\Admin\V3\System;

use App\Controller\BaseController;
use Ezijing\PermissionPlugins\Middlewares\PermissionMiddleware;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;

/**
 * 使用手册管理.
 *
 * @Controller(prefix="admin/v3/system")
 * @Middlewares({
 *     @Middleware(PermissionMiddleware::class)
 * })
 *
 */
class UserManualController extends BaseController
{}
```
