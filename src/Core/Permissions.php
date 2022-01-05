<?php

declare(strict_types=1);

namespace Ezijing\PermissionPlugins\Core;

use Ezijing\PermissionPlugins\Exceptions\ErrorCode;
use Ezijing\PermissionPlugins\Exceptions\PluginException;
use Hyperf\Config\Annotation\Value;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Framework\Logger\StdoutLogger;

class Permissions
{
    /**
     * @Inject
     * @var StdoutLogger
     */
    protected $logger;

    /**
     * @Value("permission_plugins")
     */
    protected $config;

    public function __construct()
    {
        $this->verifyTheConfiguration();
    }

    /**
     * 权限验证.
     *
     * @return bool
     */
    public function checkPermission()
    {
        // 获取当前路由
        $route = getRoutePath();

        // 获取所有可访问的路由
        $permissionRoutes = $this->getPermissions();
        $routeArr = array_flip($permissionRoutes);

        if (! isset($routeArr[$route])) {
            return false;
        }

        return true;
    }

    /**
     * 从统一权限系统获取权限.
     *
     * @return mixed
     */
    public function getPermissions()
    {
        // 初始化参数
        $apiConfig = $this->config['API'];
        $method = $apiConfig['ROUTES']['METHOD'];
        $host = sprintf('%s%s', $this->config['HOST'], $apiConfig['ROUTES']['API']);
        $timestamp = time();
        $nonce = md5(uniqid('ezijing_', true));
        $data = [];
        $header = [
            'Cookie' => sprintf('TGC=%s', (string) $this->getTgc()),
            'timestamp' => $timestamp,
            'secret-id' => $this->config['SECRET_ID'],
            'secret-key' => $this->config['SECRET_KEY'],
            'signature' => $this->getSignature($timestamp, $nonce, $this->config['SECRET_KEY'], $data),
            'nonce' => $nonce,
        ];

        // 获取权限
        $res = requestClient($method, $host, $data, $header);

        return $this->formatRes($res);
    }

    /**
     * 接口签名.
     *
     * @param $nonce
     * @param $secretKey
     * @param $timestamp
     * @return string
     */
    protected function getSignature($timestamp, $nonce, $secretKey, array $data = [])
    {
        $data['timestamp'] = $timestamp;
        $data['nonce'] = $nonce;
        $data['salt'] = $secretKey;

        ksort($data);

        $query = '';
        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $query .= $k . '=' . json_encode($v) . '&';
            } else {
                $query .= $k . '=' . $v . '&';
            }
        }
        $query = substr($query, 0, strlen($query) - 1);

        return strtoupper(md5($query));
    }

    /**
     * 获取cookie中的TGC.
     *
     * @return mixed
     */
    protected function getTgc()
    {
        return getHttpRequest()->cookie('TGC', '');
    }

    /**
     * 验证权限配置.
     */
    protected function verifyTheConfiguration()
    {
        if (is_null($this->config) || empty($this->config)) {
            throw new PluginException(ErrorCode::PERMISSION_CONFIGURATION_ERROR);
        }
    }

    /**
     * 格式化响应的数据.
     *
     * @param $res
     * @return mixed
     */
    private function formatRes($res)
    {
        if ($res['code'] != 0 || ! isset($res['data'])) {
            throw new PluginException(ErrorCode::PERMISSION_OBTAINING_FAILURE, $res['message']);
        }

        if (! isset($res['data']['items'])) {
            throw new PluginException(ErrorCode::PERMISSION_OBTAINING_FAILURE, '获取权限接口发生变更');
        }

        if (! is_array($res['data']['items'])) {
            throw new PluginException(ErrorCode::PERMISSION_OBTAINING_FAILURE, '获取权限接口发生变更');
        }

        return $res['data']['items'];
    }
}
