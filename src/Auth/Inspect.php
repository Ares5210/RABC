<?php

namespace Ares\Rabc\Auth;

use Ares\Rabc\Rabc;
use Ares\Rabc\Model\Access;
use Ares\Rabc\Model\Rule;
use Ares\Rabc\Model\Group;
use Ares\Rabc\Functions;

/**
 * 检查用户权限
 * Class Inspect
 * @package Ares\Rabc\Auth
 */
class Inspect
{
    /**
     * 检查管理员是否有权限
     * @param string $url
     * @param int $admin_id
     * @param bool $isId
     * @return bool
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function check(string $url, int $admin_id, bool $isId = false)
    {
        $super_admin_id = Rabc::getConfig('SuperAdminId') ?? array();
        if (in_array($admin_id, $super_admin_id)) {
            return true;
        }

        $white_list = Rabc::getConfig('WhiteList') ?? array();
        if (in_array($url, $white_list)) {
            return true;
        }

        // 获取权限
        $rule = (new Rule())->getRowByName(Functions::reUrl($url, $isId));
        if (!$rule) {
            Functions::outputQueryExceptions($url . '权限不存在');
        }
        $rule_id = $rule['id'];

        // 查看用户属于哪个用户组
        $access = (new Access())->getRowByAdminId($admin_id);
        if (!$access) {
            return false;
        }
        $group_id = $access['group_id'];

        $group = (new Group())->getRowById($group_id);
        if (!$group) {
            Functions::outputQueryExceptions('管理员分配的角色不存在');
        }

        if (in_array($rule_id, explode(',', $group['rules']))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 菜单
     * @param int $admin_id
     * @return array|bool
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function menu(int $admin_id)
    {
        $super_admin_id = Rabc::getConfig('SuperAdminId') ?? array();
        $Model = new Rule();
        if (in_array($admin_id, $super_admin_id)) {
            $rules = $Model->getAll();
        } else {
            // 查看用户属于哪个用户组
            $access = (new Access())->getRowByAdminId($admin_id);
            if (!$access) {
                return false;
            }
            $group_id = $access['group_id'];

            $group = (new Group())->getRowById($group_id);
            if (!$group) {
                Functions::outputQueryExceptions('管理员分配的角色不存在');
            }
            $rules_id = explode(',', $group['rules']);
            $rules = $Model->getListByIds($rules_id);
        }

        return $this->get_rule_list($rules, 0);
    }

    /**
     * @param $rules
     * @param $pid
     * @return array
     */
    private function get_rule_list(&$rules, $pid)
    {
        $result = array();
        if (empty($rules)) return $result;
        foreach ($rules as $rule) {
            if ($rule['parent_id'] == $pid && $rule['is_menu'] == 1) {
                $result[$rule['id']] = $rule;
                $result[$rule['id']]['items'] = $this->get_rule_list($rules, $rule['id']);
            }
        }
        return $result;
    }
}