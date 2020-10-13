<?php
namespace App\Utils;
use App\Exceptions\ApiException;

class CommonUtil
{
    /**
     * @param $code
     * @param $msg
     * @throws ApiException
     */
    public static function throwException($code, $msg)
    {
        throw new ApiException($code, $msg);
    }
}