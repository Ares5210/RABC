<?php

namespace Ares\Rabc\Model;

use Ares\Rabc\Functions;
use think\Db;
use Ares\Rabc\Rabc;

/**
 * 权限管理
 * Class Group
 * @package Ares\Rabc\Model
 */
class Group extends Model
{
    /**
     * 配置表名
     * Group constructor.
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct();

        if ($table_name = Rabc::getConfig('GroupTable')) {
            $this->table_name = $table_name;
        } else {
            Functions::outputParamsExceptions('GroupTable' . '参数不存在');
        }
    }

    /**
     * 根据id获取数据
     * @param $id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRowById($id)
    {
        return Db::table($this->table_name)
            ->where("id = $id AND status = 1")
            ->find();
    }

    /**
     * 获取所有角色
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll()
    {
        return Db::table($this->table_name)
            ->field("t1.*,count(t2.group_id) as num")
            ->alias('t1')
            ->leftJoin('kf_admin_auth_group_access t2', 't1.id = t2.group_id')
            ->group('t1.id')
            ->select();
    }

    /**
     * 获取角色数量
     * @return int|string
     */
    public function getCount()
    {
        return Db::table($this->table_name)
            ->count();
    }
}