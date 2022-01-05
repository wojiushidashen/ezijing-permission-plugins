<?php

declare(strict_types=1);

namespace Ezijing\PermissionPlugins\Exceptions;

use Throwable;

class PluginException extends \RuntimeException
{
    public function __construct($code = 0, $message = '', Throwable $previous = null)
    {
        if ($message && ErrorCode::getCodeMessage($code)) {
            $message = ErrorCode::getCodeMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }
}
