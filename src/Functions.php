<?php

namespace Ares\Rabc;

use Ares\Rabc\Exceptions\InvalidArgumentException;

class Functions
{
    public static function outputParamsExceptions($msg = '参数错误')
    {
        throw new InvalidArgumentException($msg);
    }

    public static function outputQueryExceptions($msg = '数据错误')
    {
        throw new InvalidArgumentException($msg);
    }
}