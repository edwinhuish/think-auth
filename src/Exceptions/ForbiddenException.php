<?php

/**
 * @author Edwin Xu <171336747@qq.com>
 * @version 2022-10-29
 */

declare(strict_types=1);

namespace Edwinhuish\ThinkAuth\Exceptions;

use Exception;
use think\exception\HttpException;

class ForbiddenException extends HttpException
{
    public function __construct(string $message = '', Exception $previous = null, array $headers = [], $code = 1)
    {
        parent::__construct(403, $message, $previous, $headers, $code);
    }
}
