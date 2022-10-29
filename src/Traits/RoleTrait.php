<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Traits;

use think\facade\Db;
use think\Model;
use think\model\relation\BelongsToMany;

trait RoleTrait
{
    /**
     * 为当前角色关联一个权限.
     *
     * @param int|string|Model $permission
     *
     * @return $this
     */
    public function attachPermission($permission)
    {
        if (is_string($permission)) {
            $permission = call_user_func([config('auth.permission.model'), 'findByName'], $permission);
        }

        $this->permissions()->attach($permission);

        return $this;
    }

    /**
     * 增加多个权限.
     *
     * @param int[]|string[]|Model[] $permissions
     *
     * @return $this
     */
    public function attachPermissions($permissions)
    {
        $ids = [];

        $names = [];

        foreach ($permissions as $perm) {
            if (is_numeric($perm)) {
                $ids[] = $perm;
                continue;
            }

            if (is_string($perm)) {
                $names[] = $perm;
                continue;
            }

            if ($perm instanceof Model) {
                $ids[] = $perm->getKey();
                continue;
            }
        }

        /** @var Query $query */
        $query = Db::name(config('auth.permission.table'));

        $ids = array_merge($ids, $query->where('name', 'IN', $names)->select()->column('id'));

        $this->permissions()->attach($ids);

        return $this;
    }

    /**
     * 将当前角色关联到指定用户.
     *
     * @param int|string|Model $user
     *
     * @return $this
     */
    public function attachUser($user)
    {
        if (is_string($user)) {
            $user = call_user_func([config('auth.user.model'), 'findByName'], $user);
        }

        $this->users()->attach($user);

        return $this;
    }

    /**
     * 为当前角色移除所有权限.
     *
     * @return $this
     */
    public function detachAllPermission()
    {
        $this->permissions()->detach();

        return $this;
    }

    /**
     * 为当前角色移除一个权限.
     *
     * @param int|string|Model $permission
     *
     * @return $this
     */
    public function detachPermission($permission)
    {
        if (is_string($permission)) {
            $permission = call_user_func([config('auth.permission.model'), 'findByName'], $permission);
        }

        $this->permissions()->detach($permission);

        return $this;
    }

    /**
     * 删除多个权限.
     *
     * @param int[]|string[]|Model[] $permissions
     *
     * @return $this
     */
    public function detachPermissions($permissions)
    {
        $ids = [];

        $names = [];

        foreach ($permissions as $perm) {
            if (is_numeric($perm)) {
                $ids[] = $perm;
                continue;
            }

            if (is_string($perm)) {
                $names[] = $perm;
                continue;
            }

            if ($perm instanceof Model) {
                $ids[] = $perm->getKey();
                continue;
            }
        }

        /** @var Query $query */
        $query = Db::name(config('auth.permission.table'));

        $ids = array_merge($ids, $query->where('name', 'IN', $names)->select()->column('id'));

        $this->permissions()->detach($ids);

        return $this;
    }

    /**
     * 角色与用户解除关系.
     *
     * @param int|string|Model $user
     *
     * @return $this
     */
    public function detachUser($user)
    {
        if (is_string($user)) {
            $user = call_user_func([config('auth.user.model'), 'findByName'], $user);
        }

        $this->users()->detach($user);

        return $this;
    }

    /**
     * 通过名称查找角色.
     *
     * @return $this
     */
    public static function findByName(string $name)
    {
        return static::where(['name' => $name])->find();
    }

    /**
     * 获取角色下所有权限.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.permission.model'),
            config('auth.pivot.role_permission_access'),
            config('auth.permission.froeign_key'),
            config('auth.role.froeign_key')
        );
    }

    /**
     * 获取角色下所有用户.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.user.model'),
            config('auth.pivot.user_role_access'),
            config('auth.user.froeign_key'),
            config('auth.role.froeign_key')
        );
    }
}
