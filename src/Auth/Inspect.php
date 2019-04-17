<?php

namespace Ares\Rabc\Auth;

use Ares\Rabc;
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
     * @param $url
     * @param $admin_id
     * @return bool
     * @throws Rabc\Exceptions\InvalidArgumentException
     */
    public function check($url, $admin_id)
    {
        $super_admin_id = Rabc\Rabc::getConfig('SuperAdminId') ?? 1;
        if ($super_admin_id == $admin_id) {
            return true;
        }

        // 获取权限id
        $rule = (new Rule())->getRowByName($url);
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
}