<?php

namespace Ares\Rabc;

use Ares\Rabc\Exceptions\InvalidArgumentException;

/**
 * 公共方法
 * Class Functions
 * @package Ares\Rabc
 */
class Functions
{
    /**
     * 抛出参数错误
     * @param string $msg
     * @throws InvalidArgumentException
     */
    public static function outputParamsExceptions($msg = '参数错误')
    {
        throw new InvalidArgumentException($msg);
    }

    /**
     * 抛出查询错误
     * @param string $msg
     * @throws InvalidArgumentException
     */
    public static function outputQueryExceptions($msg = '数据错误')
    {
        throw new InvalidArgumentException($msg);
    }

    /**
     * 输出
     * @param array $list
     * @param int $count
     */
    public static function success(array $list, int $count = 0)
    {
        $data = [
            'data' => $list,
            'ret' => 200
        ];
        exit(json_encode($data));
    }

    /**
     * 返回成功
     */
    public static function suc()
    {
        exit(json_encode(['status' => true, 'message' => '处理成功']));
    }

    /**
     * 返回失败
     */
    public static function err($msg = '')
    {
        exit(json_encode(['status' => false, 'message' => $msg ?: '处理失败']));
    }

    /**
     * @param $url
     * @param bool $isId
     * @return string
     */
    public static function reUrl($url, $isId = false)
    {
        $urlParams = explode('/', $url);
        $urlParams = array_values(array_filter($urlParams));
        if ($isId && intval($urlParams[count($urlParams) - 1]) > 0) {
            unset($urlParams[count($urlParams) - 1]);
        }

        return '/' . implode('/', $urlParams);
    }
}