<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Contracts;

use think\Collection;
use think\model\relation\BelongsToMany;

interface UserContract
{
    /**
     * 将用户关联到指定角色.
     *
     * @return $this
     */
    public function attachRole(string $role);

    /**
     * 是否有此权限.
     */
    public function can(string $permission): bool;

    /**
     * 删除所有已绑定的角色.
     *
     * @return $this
     */
    public function detachAllRole();

    /**
     * 删除指定角色.
     *
     * @param string|string[] $role
     *
     * @return $this
     */
    public function detachRole($role);

    /**
     * 删除多个角色.
     *
     * @param int[]|string[]|Model[] $role
     *
     * @return $this
     */
    public function detachRoles($roles);

    /**
     * 按名称查找用户.
     *
     * @return $this
     */
    public static function findByName(string $name);

    /**
     * 获取用户的所有权限.
     */
    public function getPermissions(): Collection;

    /**
     * 获取缓存后的所有权限，避免多次发起 sql 请求。
     */
    public function getPermissionsCached(): array;

    /**
     * 是否绑定某个角色.
     */
    public function hasRole(string $role): bool;

    /**
     * 是否超级管理员.
     */
    public function isSuper(): bool;

    /**
     * 刷新/删除缓存.
     *
     * @return $this
     */
    public function refreshPermissionsCache();

    /**
     * 获取用户下所有角色.
     */
    public function roles(): BelongsToMany;
}
