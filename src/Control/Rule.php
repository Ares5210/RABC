<?php

namespace Ares\Rabc\Control;

use Ares\Rabc\Control;
use Ares\Rabc\Functions;
use Ares\Rabc\Model\Group as ModelGroup;
use Ares\Rabc\Model\Rule as ModelRule;

/**
 * 权限相关页面
 * Class Group
 * @package Ares\Rabc\Control
 */
class Rule extends Control
{

    /**
     * 权限列表
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        if ($_POST) {
            $Model = new ModelRule();
            $list = $Model->getAll();
            Functions::success((new Tree())->tree($list, 'title', 'id', 'parent_id'));
        } else {
            $this->render('index');
        }
    }

    /**
     * 编辑
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\PDOException
     */
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $pid = $_GET['pid'] ?? null;
        $Model = new ModelRule();
        if ($_POST) {
            $data = $this->checkData($_POST);
            if ($Model->getRowByName($data['name'], $id)) {
                Functions::err('权限已存在');
            }
            if ($id) {
                $result = $Model->edit($id, $data);
            } else {
                $pid && $data['parent_id'] = $pid;
                $result = $Model->add($data);
            }
            $result ? Functions::suc() : Functions::err('修改失败或数据相同');
        } else {
            $res = [];
            $name = null;
            if ($id) {
                $res = $Model->getRow($id);
            } elseif ($pid) {
                $data = $Model->getRow($pid);
                $name = $data['title'];
            }

            $this->render('add', compact('res', 'name'));
        }
    }

    /**
     * 新增
     */
    public function add()
    {
        $this->edit();
    }

    /**
     * 编辑
     */
    public function child()
    {
        $this->edit();
    }

    /**
     * 删除
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\Exception
     * @throws \think\db\exception\PDOException
     */
    public function close()
    {
        $id = $_POST['id'];
        $Model = new ModelRule();
        if ($Model->getListByPid($id)) {
            Functions::err('请先删除子权限');
        }
        $Model->delete($id) ? Functions::suc() : Functions::err();
    }

    /**
     * 检查数据
     * @param $data
     * @return mixed
     */
    private function checkData($data)
    {
        $data['is_menu'] = isset($data['is_menu']) ? 1 : 0;
        $data['name'] = Functions::reUrl($data['name']);
        return $data;
    }

}