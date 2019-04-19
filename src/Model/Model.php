<?php

namespace Ares\Rabc\Model;

use think\Db;
use Ares\Rabc\Rabc;

/**
 * 数据库基类
 * Class Model
 * @package Ares\Rabc\Model
 */
class Model
{
    public $list_rows = 15;
    protected $table_name;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $value = Rabc::getConfig('db');

        Db::setConfig([
            'type' => $value['type'] ?? 'mysql',
            'hostname' => $value['host'],
            'database' => $value['name'],
            'username' => $value['user'],
            'password' => $value['password'],
            'hostport' => $value['port'],
            'charset' => $value['charset']
        ]);
    }

    /**
     * 编辑
     * @param $id
     * @param $params
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     */
    public function edit($id, $params)
    {
        return Db::table($this->table_name)
            ->where('ID', $id)
            ->update($params);
    }

    /**
     * 添加
     * @param $params
     * @param bool $replace
     * @param bool $getLastInsID
     * @return int|string
     */
    public function add($params, $replace = false, $getLastInsID = true)
    {
        return Db::table($this->table_name)
            ->insert($params, $replace, $getLastInsID);
    }

    /**
     * 开启
     * @param $ID
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     */
    public function open($ID)
    {
        return $this->updateState($ID, 1) ? true : false;
    }

    /**
     * 删除
     * @param $ID
     * @return bool
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     */
    public function close($ID)
    {
        return $this->updateState($ID, 2) ? true : false;
    }

    /**
     * 修改状态
     * @param $ID
     * @param $ZT
     * @return int|string
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     */
    private function updateState($ID, $ZT)
    {
        return Db::table($this->table_name)
            ->whereIn('ID', $ID)
            ->update(['status' => $ZT]);
    }

    /**
     * 根据id获取数据
     * @param $id
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getRow($id)
    {
        return Db::table($this->table_name)
            ->where('id', $id)
            ->find();
    }
}