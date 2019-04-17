<?php

namespace Ares\Rabc\Model;

use think\Db;
use Ares\Rabc\Rabc;

class Model
{
    public $list_rows = 15;

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
}