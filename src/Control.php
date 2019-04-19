<?php

namespace Ares\Rabc;

/**
 * 控制器基类
 */
class Control
{
    protected $_view;

    function __construct()
    {
        $controller = get_called_class();
        $index = strrpos($controller, '\\') + 1;
        $controller = substr($controller, $index, strlen($controller));

        $this->_view = new Page($controller);
    }

    /**
     * 渲染页面
     * @param string $url
     * @param array $data
     */
    function render(string $url, array $data = [])
    {
        $this->_view->render($url, $data);
    }
}