<?php

declare(strict_types=1);

namespace Ezijing\PermissionPlugins\Middlewares;

use Ezijing\PermissionPlugins\Core\Permissions;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class PermissionMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var Permissions
     */
    protected $permissionPlugin;

    /**
     * @var HttpResponse
     */
    protected $response;

    public function __construct(HttpResponse $response)
    {
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (! $this->permissionPlugin->checkPermission()) {
            return $this->response->json([
                'code' => 403,
                'message' => '无访问权限',
            ]);
        }

        return $handler->handle($request);
    }
}
