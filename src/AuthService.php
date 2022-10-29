<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth;

use Edwinhuish\ThinkAuth\Command\Publish;
use Edwinhuish\ThinkAuth\Middlewares\PermissionMiddleware;
use Edwinhuish\ThinkAuth\Middlewares\RoleMiddleware;
use think\Service;

class AuthService extends Service
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/auth.php', 'auth');

        $this->commands(['auth:publish' => Publish::class]);
    }

    public function register()
    {
        // 注册数据迁移服务
        $this->app->register(\think\migration\Service::class);

        // 注册中间件
        $this->app->bind('auth', PermissionMiddleware::class);
        $this->app->bind('auth.permission', PermissionMiddleware::class);
        $this->app->bind('auth.role', RoleMiddleware::class);
    }

    protected function mergeConfigFrom(string $path, string $key)
    {
        $config = $this->app->config->get($key, []);

        $this->app->config->set(array_merge(require $path, $config), $key);
    }
}
