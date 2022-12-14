# Think Auth (ThinkPHP 6 Package)

基于角色的权限的简洁而灵活的方式。


## Contents
- [安装](#安装)
- [自定义模型](#自定义模型)
    - [Role](#role)
    - [Permission](#permission)
    - [User](#user)
- [使用](#使用)
    - [概念](#概念)
    - [检查用户是否拥有权限](#检查用户是否拥有权限)
    - [使用中间件](#使用中间件)
        
- [故障排除](#故障排除)
- [License](#license)
- [Contribution guidelines](#contribution-guidelines)


## 安装

1. 运行 `composer require edwinhuish/think-auth`

2. 使用 `php think auth:publish` 在项目内新增 `/config/auth.php` 以及在 `/database/migrations/` 内新增迁移文件。

3. 配置 `config/auth.php`，并按需修改迁移文件，`user` 表的迁移默认已注释，请根据实际需求修改。

4. 运行 `php think migrate:run` 生成数据表。


## 自定义模型

### Role

使用以下示例在`app\model\Role.php`内创建角色模型：

```php
<?php

namespace app\model;

use Edwinhuish\ThinkAuth\Contracts\RoleContract;
use Edwinhuish\ThinkAuth\Traits\RoleTrait;
use think\Model;

class Role extends Model implements RoleContract
{
    use RoleTrait;

    protected $name = 'auth_roles';
}

```

角色模型的主要属性是 `name`
- `name` &mdash; 角色的唯一名称，用于在应用程序层查找角色信息。 例如：“管理员”，“所有者”，“员工”.
- `description` &mdash; 该角色的人类可读名称。 不一定是唯一的和可选的。 例如：“用户管理员”，“项目所有者”，“Widget Co.员工”.

 `description` 字段是可选的; 他的字段在数据库中是可空的。

### Permission

使用以下示例在`app\model\Permission.php`内创建角色模型：

```php
<?php
namespace app\model;

use Edwinhuish\ThinkAuth\Contracts\PermissionContract;
use Edwinhuish\ThinkAuth\Traits\PermissionTrait;
use think\Model;

class Permission extends Model implements PermissionContract
{
    use PermissionTrait;

    protected $name = 'auth_permissions';
}

```

“权限”模型与“角色”具有相同的两个属性：
- `name` &mdash; 权限的唯一名称，用于在应用程序层查找权限信息。一般保存的是路由.
- `description` &mdash; 该权限的描述。 例如：“创建文章”，“查看文件”，“文件管理”等权限.

 `description` 字段是可选的; 他的字段在数据库中是可空的。


### User

接下来，在现有的User模型中使用`UserTrait`特征。 例如：

```php
<?php
namespace app\model;

use Edwinhuish\ThinkAuth\Contracts\UserContract;
use Edwinhuish\ThinkAuth\Traits\UserTrait;
use think\Model;

class User extends Model implements UserContract
{
    use UserTrait;

    protected $name = 'users';
}

```

这将启用与Role的关系，并在User模型中添加以下方法roles() 和 can( $permission )


不要忘记转储composer的自动加载

```bash
composer dump-autoload
```

**准备好了.**

## 使用

### 概念
让我们从创建以下`角色`和`权限`开始：

```php
$owner = new Role();
$owner->name         = 'owner';
$owner->description  = '网站所有者'; // 可选
$owner->save();

$administrator = new Role();
$administrator->name        = 'administrator';
$administrator->description = '管理员'; // 可选
$administrator->save();
```

接下来，让我们为刚刚创建的两个角色将它们分配给用户。
这很容易：

```php
$hurray = User::findByName('hurray');

/* 为用户分配角色 */
$hurray->attachRole($administrator); // model
$hurray->attachRole('administrator'); // name
$hurray->attachRole($administrator->id); // id

```

现在我们只需要为这些角色添加权限：

```php
$administrator = Role::findByName('administrator');

$owner = Role::findByName('owner');

$createPost = new Permission();
$createPost->name         = 'post/create';
$createPost->description  = '创建一篇文章'; // 可选
$createPost->save();

$editPost = new Permission();
$editPost->name         = 'post/edit';
$editPost->description  = '编辑一篇文章'; // optional
$editPost->save();

/* 为 administrator 添加权限 */
$administrator->attachPermission($createPost); // model
$administrator->attachPermission('post/create'); // name
$administrator->attachPermission($createPost->id); // id

/* 为 owner 添加多个权限 */
$owner->attachPermissions([$createPost, $editPost]); // model
$owner->attachPermissions(['post/create', 'post/edit']); // name
$owner->attachPermissions([$createPost->id, $editPost->id]); // model
```

### 检查用户是否拥有权限

现在我们可以通过执行以下操作来检查角色和权限：

```php
$hurray = User::findByName('hurray');
$hurray->can('post/edit');   // false
$hurray->can('post/create'); // true
```

小提示：本功能在保存用户和角色之间的关系和角色与权限之间的关系，为了避免大量的sql查询是使用了tp的缓存的哦，

如果你更新了他们之间的关系，记得把缓存清理一下。
```php
$hurray->refreshPermissionsCache();
```


### 使用中间件
```php
#route/app.php

# 拥有 post/edit 规则的用户 可以访问此路由
Route::rule('/testPermission', function(){
  return 'edit';
}, 'GET')->allowCrossDomain()->middleware('auth.permission', 'post/edit');
```

角色中间件
```php
#route/app.php

# 拥有 administrator 角色的用户 可以访问此路由
Route::rule('/testRole', function(){
  return 'administrator';
}, 'GET')->allowCrossDomain()->middleware('auth.role', 'administrator');

```


到目前为止，已经可以很大的满足到后台用户权限管理功能了。

本功能目前比较简单，现在作者正在扩展其他功能.

最后，如果你觉得不错，请start一个吧 你的鼓励是我创作的无限动力.

## 故障排查

如果你迁移和配置中遇到问题，可直接联企鹅号:171336747



## License

think auth is free software distributed under the terms of the MIT license.

## Contribution guidelines

Support follows PSR-1 and PSR-4 PHP coding standards, and semantic versioning.

Please report any issue you find in the issues page.  
Pull requests are welcome.
