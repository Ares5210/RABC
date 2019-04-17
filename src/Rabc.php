<?php

namespace Ares\Rabc;

class Rabc
{
    /**
     * 配置
     * @var array
     */
    protected static $config = [];

    /**
     * 设置配置
     * @param array $config
     */
    public static function setConfig($config = [])
    {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * 获取配置
     * @param null $name
     * @return array|mixed|null
     */
    public static function getConfig($name = null)
    {
        if ($name) {
            return isset(self::$config[$name]) ? self::$config[$name] : null;
        } else {
            return self::$config;
        }
    }

    public static function __callStatic($method, $args)
    {
        if ('check' == $method) {
            $class = '\\Ares\\Rabc\\Auth\\Inspect';
        }

        return call_user_func_array([new $class, $method], $args);
    }
}