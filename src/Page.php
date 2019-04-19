<?php

namespace Ares\Rabc;

/**
 * 视图基类
 */
class Page
{
    protected $_controller;
    protected $_path;

    function __construct($controller)
    {
        $this->_controller = $controller;

        $path = Rabc::getConfig('StylePath');
        if (!$path) {
            Functions::outputParamsExceptions('StylePath' . '是必要参数');
        }
        $this->_path = $path;
    }

    /**
     * 渲染页面
     * @param string $url
     * @param array $data
     */
    function render(string $url, array $data = [])
    {
        $app_path = __DIR__ . '/';
        $path = $this->_path;
        extract($data);

        // 页内容文件
        include($app_path . 'Pages/' . $this->_controller . '/' . $url . '.php');

        exit();
    }
}