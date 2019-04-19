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
    /**
     * 配置表名
     * Access constructor.
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct();

        if ($table_name = Rabc::getConfig('AccessTable')) {
            $this->table_name = $table_name;
        } else {
            Functions::outputParamsExceptions('AccessTable' . '参数不存在');
        }
    }

    /**
     * 根据管理员id获取数据
     * @param $admin_id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRowByAdminId($admin_id)
    {
        return Db::table($this->table_name)
            ->where('uid', $admin_id)
            ->find();
    }
}