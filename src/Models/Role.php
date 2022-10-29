<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Models;

use Edwinhuish\ThinkAuth\Contracts\RoleContract;
use Edwinhuish\ThinkAuth\Traits\RoleTrait;
use think\Model;

class Role extends Model implements RoleContract
{
    use RoleTrait;

    public function __construct(array $data = [])
    {
        $this->name = config('auth.role.table');

        parent::__construct($data);
    }
}
