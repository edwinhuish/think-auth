<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Contracts;

use think\Model;
use think\model\relation\BelongsToMany;

interface RoleContract
{
    /**
     * 为当前角色关联一个权限.
     *
     * @param int|string|Model $permission
     *
     * @return $this
     */
    public function attachPermission($permission);

    /**
     * 增加多个权限.
     *
     * @param int[]|string[]|Model[] $permissions
     *
     * @return $this
     */
    public function attachPermissions($permissions);

    /**
     * 将当前角色关联到指定用户.
     *
     * @param int|string|Model $user
     *
     * @return $this
     */
    public function attachUser($user);

    /**
     * 为当前角色移除所有权限.
     *
     * @return $this
     */
    public function detachAllPermission();

    /**
     * 为当前角色移除一个权限.
     *
     * @param int|string|Model $permission
     *
     * @return $this
     */
    public function detachPermission($permission);

    /**
     * 删除多个权限.
     *
     * @param int[]|string[]|Model[] $permissions
     *
     * @return $this
     */
    public function detachPermissions($permissions);

    /**
     * 角色与用户解除关系.
     *
     * @param int|string|Model $user
     *
     * @return $this
     */
    public function detachUser($user);

    /**
     * 通过名称查找角色.
     *
     * @return $this
     */
    public static function findByName(string $name);

    /**
     * 获取角色下所有权限.
     */
    public function permissions(): BelongsToMany;

    /**
     * 获取角色下所有用户.
     */
    public function users(): BelongsToMany;
}
