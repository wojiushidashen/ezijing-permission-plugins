<?php

declare(strict_types=1);

if (! function_exists('container')) {
    /**
     * 获取容器实例.
     * @return \Psr\Container\ContainerInterface
     */
    function container(): Psr\Container\ContainerInterface
    {
        return \Hyperf\Utils\ApplicationContext::getContainer();
    }
}

if (! function_exists('getHttpRequest')) {
    /**
     *  获取HTTP请求。
     *
     * @return \Hyperf\HttpServer\Contract\RequestInterface
     */
    function getHttpRequest(): Hyperf\HttpServer\Contract\RequestInterface
    {
        return container()->get(\Hyperf\HttpServer\Request::class);
    }
}

if (! function_exists('getRoutePath')) {
    /**
     * 获取访问的路由.
     *
     * @return string
     */
    function getRoutePath()
    {
        $dispatcher = getHttpRequest()->getAttribute('Hyperf\HttpServer\Router\Dispatched');

        return sprintf('%s:%s', getHttpRequest()->getMethod(), $dispatcher->handler->route ?? getHttpRequest()->getRequestUri());
    }
}

if (! function_exists('getEventDispatcherFactory')) {
    /**
     * 获取事件分发器.
     *
     * @return \Psr\EventDispatcher\EventDispatcherInterface
     */
    function getEventDispatcherFactory()
    {
        return container()->get(\Hyperf\Event\EventDispatcher::class);
    }
}

if (! function_exists('requestClient')) {
    // 发送http请求
    function requestClient($method, $url, array $data = [], array $header = [], array $creatOptions = [])
    {
        $clientFactory = container()->get(\Hyperf\Guzzle\ClientFactory::class);
        $client = $clientFactory->create(array_merge($creatOptions, ['timeout' => 20]));
        $options = [];
        if ($tgc = \Hyperf\Utils\Context::get('tgc')) {
            $header['Cookie'] = "TGC={$tgc}";
        }
        if (! empty($header)) {
            $options['headers'] = $header;
        }
        switch (strtolower($method)) {
            case 'get':
                if (! empty($data)) {
                    $options['query'] = $data;
                }
                $result = $client->get($url, $options)->getBody()->getContents();
                break;
            case 'post':
                if (! empty($data)) {
                    if (isset($header['Content-Type']) && strpos($header['Content-Type'], 'application/x-www-form-urlencoded') !== false) {
                        $options['form_params'] = $data;
                    } else {
                        $options['json'] = $data;
                    }
                }
                $result = $client->post($url, $options)->getBody()->getContents();
                break;
            case 'delete':
                if (! empty($data)) {
                    $options['query'] = $data;
                }
                $result = $client->delete($url, $options)->getBody()->getContents();
                break;
            case 'put':
                if (! empty($data)) {
                    if (isset($header['Content-Type']) && strpos($header['Content-Type'], 'application/x-www-form-urlencoded') !== false) {
                        $options['form_params'] = $data;
                    } else {
                        $options['json'] = $data;
                    }
                }
                $result = $client->put($url, $options)->getBody()->getContents();
                break;
            case 'patch':
                if (! empty($data)) {
                    if (isset($header['Content-Type']) && strpos($header['Content-Type'], 'application/x-www-form-urlencoded') !== false) {
                        $options['form_params'] = $data;
                    } else {
                        $options['json'] = $data;
                    }
                }
                $result = $client->patch($url, $options)->getBody()->getContents();
                break;
            default:
                throw new \Ezijing\EzijingSso\Exceptions\PluginException(
                    \Ezijing\EzijingSso\Constants\ErrorCode::REQUEST_ERROR
                );
        }

        return \Hyperf\Utils\Codec\Json::decode($result ?? '[]', true);
    }
}
