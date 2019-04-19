<h1 align="center"> RABC </h1>

<p align="center">🤗基于layui傻瓜式的RABC。</p>

## 安装

```shell
$ composer require ares/rabc -vvv
```

## 使用

目前前台样式只支持layui。

```php
use Ares\Rabc\Rabc;

Rabc::setConfig([
    'db' => [ // 数据库配置
        'type' => 'mysql',
        'hostname' => 'host',
        'database' => 'name',
        'username' => 'user',
        'password' => 'password',
        'hostport' => 'port',
        'charset' => 'charset'
    ],
    'SuperAdminId' => array(1), // 超级管理员
    'WhiteList' => array('/index/home', '/'), // 白名单
    'GroupTable' => 'auth_group', // 角色表
    'AccessTable' => 'auth_group_access', // 管理员与角色关系表
    'RuleTable' => 'auth_rule', // 权限表
    'StylePath' => '/static/layuiadmin/', // layui样式地址
]);
```

### 检查权限
```php
if (Rabc::check($url, $user_id, $isId)) {
    exit('您没有权限访问此功能~');
}
// $url string 权限url
// $user_id int 用户id
// $isId bool url后面是否带有id，为true则可以过滤掉，比如：/user/info/1
```

### 获取菜单
```php
Rabc::menu($this->user_id)
```