<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Traits;

use think\Collection;
use think\db\Query;
use think\facade\Cache;
use think\Model;
use think\model\relation\BelongsToMany;

trait UserTrait
{
    /**
     * 将用户关联到指定角色.
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
     * 检查是否有此权限.
     */
    public function can(string $permission): bool
    {
        if ($this->isSuper()) {
            return true;
        }

        $permission = collect($this->getPermissionsCached())->where('name', $permission)->first();

        return ! empty($permission);
    }

    /**
     * 删除所有已绑定的角色.
     */
    public function detachAllRole()
    {
        $this->roles()->detach();

        return $this;
    }

    /**
     * 删除指定角色.
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
     * 删除多个角色.
     *
     * @param int[]|string[]|Model[] $roles
     *
     * @return $this
     */
    public function detachRoles($roles)
    {
        $ids = [];

        $roleNames = [];

        foreach ($roles as $role) {
            if (is_numeric($role)) {
                $ids[] = $role;
                continue;
            }

            if (is_string($role)) {
                $roleNames[] = $role;
                continue;
            }

            if ($role instanceof Model) {
                $ids[] = $role->getKey();
                continue;
            }
        }

        /** @var Query $query */
        $query = call_user_func([config('auth.role.model'), 'db']);

        $ids = array_merge($ids, $query->where('name', 'IN', $roleNames)->select()->column('id'));

        $this->roles()->detach($ids);

        return $this;
    }

    /**
     * 获取用户.
     *
     * @return $this
     */
    public static function findByName(string $name)
    {
        return static::where(['name' => $name])->find();
    }

    /**
     * 获取用户的所有权限.
     */
    public function getPermissions(): Collection
    {
        // 超级管理员 默认全部规则
        if ($this->isSuper()) {
            return call_user_func([config('auth.permission.model'), 'select']);
        }

        $permissions = [];

        $roleIds = collect($this->roles)->column('id');

        $roleTableName = config('auth.role.table');

        /** @var Query $query */
        $query = call_user_func([config('auth.permission.model'), 'db']);

        $permissions = $query->via('perm')->alias('perm')->field('perm.*')
            ->join(config('auth.pivot.role_permission_access') . ' pivot', 'pivot.permission_id = perm.id')
            ->join("{$roleTableName} role", 'role.id = pivot.role_id')
            ->where('role.id', 'IN', $roleIds)
            ->select();

        return $permissions;
    }

    /**
     * 获取缓存后的所有权限，避免多次发起 sql 请求。
     */
    public function getPermissionsCached(): array
    {
        return Cache::remember($this->getCacheKey(), function () {
            return $this->getPermissions()->toArray();
        }, config('auth.cache.expire'));
    }

    /**
     * 是否有此角色.
     */
    public function hasRole(string $role): bool
    {
        $role = collect($this->roles)->where('name', $role)->first();

        return ! empty($role);
    }

    /**
     * 是否超级管理员.
     */
    public function isSuper(): bool
    {
        return $this->hasRole('super');
    }

    /**
     * 刷新/删除缓存.
     *
     * @return $this
     */
    public function refreshPermissionsCache()
    {
        Cache::delete($this->getCacheKey());

        return $this;
    }

    /**
     * 获取用户下所有角色.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            config('auth.role.model'),
            config('auth.pivot.user_role_access'),
            config('auth.role.froeign_key'),
            config('auth.user.froeign_key')
        );
    }

    private function getCacheKey()
    {
        return 'user_' . $this->getKey() . '_permissions';
    }
}
