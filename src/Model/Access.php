<?php

namespace Ares\Rabc\Model;

use Ares\Rabc\Functions;
use think\Db;
use Ares\Rabc\Rabc;

/**
 * 用户和组关系表
 * Class Access
 * @package Ares\Rabc\Model
 */
class Access extends Model
{
    private $table_name;

    public function __construct()
    {
        if ($table_name = Rabc::getConfig('AccessTable')) {
            $this->table_name = $table_name;
        } else {
            Functions::outputParamsExceptions('AccessTable' . '参数不存在');
        }
    }

    public function getRowByAdminId($admin_id)
    {
        return Db::table($this->table_name)
            ->where('uid', $admin_id)
            ->find();
    }
}