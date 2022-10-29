<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Contracts;

use think\Model;
use think\model\relation\BelongsToMany;

interface PermissionContract
{
    /**
     * 将当前权限关联到指定角色.
     *
     * @param int|string|Model $role
     *
     * @return $this
     */
    public function attachRole($role);

    /**
     * 移除已分配的角色.
     *
     * @param int|string|Model $role
     *
     * @return $this
     */
    public function detachRole($role);

    /**
     * 通过名称查找规则.
     *
     * @return static
     */
    public static function findByName(string $name);

    /**
     * 获取有此权限的角色列表.
     */
    public function roles(): BelongsToMany;
}
