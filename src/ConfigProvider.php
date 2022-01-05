<?php

declare(strict_types=1);

namespace Ezijing\PermissionPlugins;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => '发布权限插件配置文件.',
                    'source' => __DIR__ . '/../publish/permission_plugins.php',
                    'destination' => BASE_PATH . '/config/autoload/permission_plugins.php',
                ],
            ],
        ];
    }
}
