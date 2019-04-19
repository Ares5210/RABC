<?php
// +----------------------------------------------------------------------
// | Github ( https://github.com/Ares5210/RABC )
// +----------------------------------------------------------------------
// | Author: Wenjie <ares_5210@163.com>
// +----------------------------------------------------------------------


namespace Ares\Rabc;

use Ares\Rabc\Auth\Inspect;
use Ares\Rabc\Control\Group;

/**
 * Class Rabc
 * @package Ares\Rabc
 * @method Inspect check(string $url, int $admin_id, bool $isId) 检查管理员是否有权限
 * @method Inspect menu(int $admin_id) 获取菜单
 * @method Group group_index() 角色列表
 * @method Group group_add() 角色添加
 * @method Group group_edit() 角色编辑
 * @method Group group_open() 角色启用
 * @method Group group_close() 角色关闭
 * @method Group group_allot() 分配权限
 * @method Group rule_index() 权限列表
 * @method Group rule_add() 权限添加
 * @method Group rule_edit() 权限修改
 * @method Group rule_close() 删除权限
 * @method Group rule_child() 添加子权限
 */
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

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (in_array($method, ['check', 'menu'])) {
            $class = '\\Ares\\Rabc\\Auth\\Inspect';
        } else if (in_array($method, ['group_index', 'group_add', 'group_edit', 'group_open', 'group_close', 'group_allot'])) {
            $class = '\\Ares\\Rabc\\Control\\Group';
            $method = explode('_', $method);
            $method = $method[1];
        } else if (in_array($method, ['rule_index', 'rule_add', 'rule_edit', 'rule_close', 'rule_child'])) {
            $class = '\\Ares\\Rabc\\Control\\Rule';
            $method = explode('_', $method);
            $method = $method[1];
        }

        return call_user_func_array([new $class, $method], $args);
    }
}