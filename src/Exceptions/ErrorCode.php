<?php

declare(strict_types=1);

namespace Ezijing\PermissionPlugins\Exceptions;

class ErrorCode
{
    /**
     * @var int 错误
     */
    public const ERROR = -1;

    /**
     * @var int 权限不足
     */
    public const INSUFFICIENT_PRIVILEGES = 403;

    /**
     * @var int 参数错误
     */
    public const PARAMETER_ERROR = 4007;

    /**
     * @var int 权限配置错误
     */
    public const PERMISSION_CONFIGURATION_ERROR = 5017;

    /**
     * @var int 权限获取失败
     */
    public const PERMISSION_OBTAINING_FAILURE = 5018;

    /**
     * 获取状态码对应的message.
     *
     * @return string
     */
    public static function getCodeMessage(int $errorCode = -1)
    {
        $map = [
            self::ERROR => 'FAIL',
            self::INSUFFICIENT_PRIVILEGES => '权限不足',
            self::PARAMETER_ERROR => '参数错误',
            self::PERMISSION_CONFIGURATION_ERROR => '权限配置错误',
        ];

        return $map[$errorCode] ?? '';
    }
}
