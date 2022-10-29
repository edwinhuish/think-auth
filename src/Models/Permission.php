<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Models;

use Edwinhuish\ThinkAuth\Contracts\PermissionContract;
use Edwinhuish\ThinkAuth\Traits\PermissionTrait;
use think\Model;

class Permission extends Model implements PermissionContract
{
    use PermissionTrait;

    public function __construct(array $data = [])
    {
        $this->name = config('auth.permission.table');

        parent::__construct($data);
    }
}
