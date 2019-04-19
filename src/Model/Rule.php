<?php

namespace Ares\Rabc\Model;

use Ares\Rabc\Functions;
use think\Db;
use Ares\Rabc\Rabc;

/**
 * 权限路由
 * Class Rule
 * @package Ares\Rabc\Model
 */
class Rule extends Model
{
    /**
     * 配置表名
     * Rule constructor.
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct();

        if ($table_name = Rabc::getConfig('RuleTable')) {
            $this->table_name = $table_name;
        } else {
            Functions::outputParamsExceptions('RuleTable' . '参数不存在');
        }
    }

    /**
     * 根据url获取路由
     * @param $name
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRowByName($name, $notId = null)
    {
        $where = "name = '$name'";
        $notId && $where .= " AND id != $notId";
        return Db::table($this->table_name)
            ->where($where)
            ->find();
    }

    /**
     * 根据父id获取数据
     * @param $pid
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByPid($pid)
    {
        return Db::table($this->table_name)
            ->where("parent_id = $pid")
            ->select();
    }

    /**
     * 获取所有权限
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll()
    {
        return Db::table($this->table_name)
            ->field("*,parent_id as pid")
            ->select();
    }

    /**
     * @param $ids
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getListByIds($ids)
    {
        return Db::table($this->table_name)
            ->whereIn('id', $ids)
            ->select();
    }

    /**
     * 删除权限
     * @param $id
     * @return int
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     */
    public function delete($id)
    {
        return Db::table($this->table_name)
            ->whereIn('id', $id)
            ->delete();
    }
}