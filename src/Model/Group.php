<?php

namespace Ares\Rabc\Model;

use Ares\Rabc\Functions;
use think\Db;
use Ares\Rabc\Rabc;

class Group extends Model
{
    private $table_name;

    public function __construct()
    {
        if ($table_name = Rabc::getConfig('GroupTable')) {
            $this->table_name = $table_name;
        } else {
            Functions::outputParamsExceptions('GroupTable' . '参数不存在');
        }
    }

    public function getRowById($id)
    {
        return Db::table($this->table_name)
            ->where("id = $id AND status = 1")
            ->find();
    }
}