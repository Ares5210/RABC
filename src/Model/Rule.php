<?php

namespace Ares\Rabc\Model;

use Ares\Rabc\Functions;
use think\Db;
use Ares\Rabc\Rabc;

class Rule extends Model
{
    private $table_name;

    public function __construct()
    {
        if ($table_name = Rabc::getConfig('RuleTable')) {
            $this->table_name = $table_name;
        } else {
            Functions::outputParamsExceptions('RuleTable' . '参数不存在');
        }
    }

    public function getRowByName($name)
    {
        return Db::table($this->table_name)
            ->where("name = $name AND status = 1")
            ->find();
    }
}