<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Traits;

use think\Model;
use think\model\relation\BelongsToMany;

trait PermissionTrait
{
    /**
     * 将当前权限关联到指定角色.
     *
     * @param int|string|Model $role
     *
     * @return $this
     */
    public function attachRole($role)
    {
        if (is_string($role)) {
            $role = call_user_func([config('auth.role.model'), 'findByName'], $role);
        }

        $this->roles()->attach($role);

        return $this;
    }

    /**
     * 移除已分配的角色.
     *
     * @param int|string|Model $role
     *
     * @return $this
     */
    public function detachRole($role)
    {
        if (is_string($role)) {
            $role = call_user_func([config('auth.role.model'), 'findByName'], $role);
        }

        $this->roles()->detach($role);

        return $this;
    }

    /**
     * 通过名称查找规则.
     *
     * @return static
     */
    public static function findByName(string $name)
    {
        return static::where(['name' => $name])->find();
    }

    /**
     * 获取权限所有的角色列表.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.role.model'),
            config('auth.pivot.role_permission_access'),
            config('auth.role.froeign_key'),
            config('auth.permission.froeign_key')
        );
    }
}
