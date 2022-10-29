<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

return [

    /*
     * 用户
     */
    'user' => [
        'model' => \Edwinhuish\ThinkAuth\Models\User::class,
        'table' => 'user',
        'foreign_key' => 'user_id',
    ],

    /*
    * 角色
    */
    'role' => [
        'model' => \Edwinhuish\ThinkAuth\Models\Role::class,
        'table' => 'role',
        'foreign_key' => 'role_id',
    ],

    /*
     * 权限
     */
    'permission' => [
        'model' => \Edwinhuish\ThinkAuth\Models\Permission::class,
        'table' => 'permission',
        'foreign_key' => 'permission_id',
    ],

    /*
     * 中间表
     */
    'pivot' => [
        'role_permission_access' => 'rel_role_permission',
        'user_role_access' => 'rel_user_role',
    ],

    /*
     * 缓存
     */
    'cache' => [
        'expire' => 60, // 秒
    ],

];
