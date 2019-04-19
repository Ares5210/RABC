<h1 align="center"> RABC </h1>

<p align="center">🤗基于layui傻瓜式的RABC。</p>

## 安装

```shell
$ composer require ares/rabc -vvv
```

## 数据库
```mysql
# 角色表
CREATE TABLE `auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '' COMMENT '显示名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `rules` text NOT NULL COMMENT '规则',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

# 管理员与角色关系表
CREATE TABLE `auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `group_id` mediumint(8) unsigned NOT NULL COMMENT '组ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# 权限表
CREATE TABLE `auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '' COMMENT '权限标记',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '显示名称',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `add_condition` char(100) NOT NULL DEFAULT '' COMMENT '条件',
  `parent_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '父id',
  `is_menu` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否菜单',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
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

### 角色管理
直接将下列代码分别放到控制器方法中即可，系统会自己生成页面。
注：当 `URL` 为 `www.xxx.com/group/index` 时，`index` 不能省略。
```php
Rabc::group_index(); // 角色列表
Rabc::group_edit(); // 角色编辑
Rabc::group_add(); // 角色添加
Rabc::group_allot(); // 分配权限
Rabc::group_open(); // 启用角色
Rabc::group_close(); // 删除角色
```
示例：
```php
 class GroupController
 {
    public function index()
    {
        Rabc::group_index();
    }
 }
```

### 权限管理
```php
Rabc::rule_index(); // 权限列表
Rabc::rule_add(); // 权限添加
Rabc::rule_edit(); // 权限修改
Rabc::rule_close(); // 删除权限
Rabc::rule_child(); // 添加子权限
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

根据每个管理员的权限情况分配菜单

```php
Rabc::menu($this->user_id)
```

示例：
```php
array (
  14 => 
  array (
    'id' => 14,
    'name' => '/system',
    'title' => '系统管理',
    'status' => 1,
    'add_condition' => '',
    'parent_id' => 0,
    'is_menu' => 1,
    'items' => 
    array (
      16 => 
      array (
        'id' => 16,
        'name' => '/sys/admin',
        'title' => '管理员',
        'status' => 1,
        'add_condition' => '',
        'parent_id' => 14,
        'is_menu' => 1,
        'items' => 
        array (
          18 => 
          array (
            'id' => 18,
            'name' => '/sys/group/index',
            'title' => '角色管理',
            'status' => 1,
            'add_condition' => '',
            'parent_id' => 16,
            'is_menu' => 1,
            'items' => 
            array (
            ),
          ),
          19 => 
          array (
            'id' => 19,
            'name' => '/sys/rule/index',
            'title' => '权限管理',
            'status' => 1,
            'add_condition' => '',
            'parent_id' => 16,
            'is_menu' => 1,
            'items' => 
            array (
            ),
          ),
        ),
      ),
    ),
  ),
)
```