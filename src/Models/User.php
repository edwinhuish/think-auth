<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Models;

use Edwinhuish\ThinkAuth\Contracts\UserContract;
use Edwinhuish\ThinkAuth\Traits\UserTrait;
use think\Model;

class User extends Model implements UserContract
{
    use UserTrait;

    public function __construct(array $data = [])
    {
        $this->name = config('auth.user.table');

        parent::__construct($data);
    }
}
