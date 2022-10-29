<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Middlewares;

use Edwinhuish\ThinkAuth\Contracts\UserContract;
use Edwinhuish\ThinkAuth\Exceptions\ForbiddenException;
use Edwinhuish\ThinkAuth\Exceptions\UnauthorizedException;

/**
 * 角色权限中间件.
 */
class RoleMiddleware
{
    public function handle($request, \Closure $next, $role)
    {
        /** @var UserContract $user */
        $user = $request->user;

        if (! $user) {
            throw new UnauthorizedException('用户未登录。');
        }

        if (! $user->hasRole($role)) {
            throw new ForbiddenException('无权进行此操作！');
        }

        return $next($request);
    }
}
