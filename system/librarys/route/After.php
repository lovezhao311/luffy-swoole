<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;

class After extends Route
{

    protected $method = 'after';

    /**
     * 获取路由参数
     * @method   getRoute
     * @DateTime 2017-08-22T16:17:33+0800
     * @return   [type]                   [description]
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     * 获取请求参数(请求内容)
     * @method   getData
     * @DateTime 2017-08-22T16:17:46+0800
     * @return   [type]                   [description]
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * 获取请求方式
     * @method   getMethod
     * @DateTime 2017-08-22T16:21:59+0800
     * @return   [type]                   [description]
     */
    public function getMethod()
    {
        return $this->method;
    }
}
