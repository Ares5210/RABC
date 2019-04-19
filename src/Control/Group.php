<?php

namespace Ares\Rabc\Control;

use Ares\Rabc\Control;
use Ares\Rabc\Functions;
use Ares\Rabc\Model\Group as ModelGroup;
use Ares\Rabc\Model\Rule;

/**
 * 角色相关页面
 * Class Group
 * @package Ares\Rabc\Control
 */
class Group extends Control
{

    /**
     * 角色列表
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        if ($_POST) {
            $Model = new ModelGroup();
            $list = $Model->getAll();
            Functions::success($list);
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
        $Model = new ModelGroup();
        if ($_POST) {
            if ($id) {
                $result = $Model->edit($id, $_POST);
            } else {
                $result = $Model->add($_POST);
            }
            $result ? Functions::suc() : Functions::err('修改失败或数据相同');
        } else {
            $res = $Model->getRow($id);
            $this->render('add', compact('res'));
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
     * 分配权限
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\PDOException
     */
    public function allot()
    {
        $id = $_GET['id'] ?? null;
        $Model = new ModelGroup();
        if ($_POST) {
            $data = ['rules' => implode(',', $_POST['group_id'])];
            $result = $Model->edit($id, $data);
            $result ? Functions::suc() : Functions::err('修改失败或数据相同');
        } else {
            $res = $Model->getRow($id);
            $rule = (new Rule())->getAll();
            $data = (new Tree())->table($rule);
            $this->render('allot', compact('data', 'res'));
        }
    }

    /**
     * 启用
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     */
    public function open()
    {
        $id = $_POST['id'];
        (new ModelGroup())->open($id) ? Functions::suc() : Functions::err();
    }

    /**
     * 删除
     * @throws \Ares\Rabc\Exceptions\InvalidArgumentException
     */
    public function close()
    {
        $id = $_POST['id'];
        (new ModelGroup())->close($id) ? Functions::suc() : Functions::err();
    }
}