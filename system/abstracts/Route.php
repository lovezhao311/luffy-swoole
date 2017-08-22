<?php
namespace luffySwoole\abstracts;

use luffySwoole\interfaces\Route as RouteInterface;
use luffyzhao\Exception;

abstract class Route implements RouteInterface
{
    /**
     * @var object 对象实例
     */
    protected static $instance;
    /**
     * 路由参数
     * @var array
     */
    protected $route = [];
    /**
     * 请求参数
     * @var array
     */
    protected $data = [];
    /**
     * 请求方式
     */
    protected $method = '';

    /**
     * 构造函数
     * @access protected
     * @param array $data 参数
     */
    protected function __construct($data = [])
    {

    }
    /**
     * 初始化
     * @access public
     * @param array $data 参数
     * @return luffyzhao\librarys\route\Task
     */
    public static function instance($data = [])
    {
        if (is_null(self::$instance)) {
            self::$instance = new static($data);
        }
        return self::$instance;
    }
    /**
     * 获取路由参数
     * @method   getRoute
     * @DateTime 2017-08-22T16:17:33+0800
     * @return   [type]                   [description]
     */
    public function getRoute()
    {
        $this->route;
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

    /**
     * 解析路由参数
     * @method   resolveRoute
     * @DateTime 2017-08-22T16:38:05+0800
     * @return   [type]                   [description]
     */
    protected function resolveRoute($data)
    {
        if (empty($data)) {
            // 空路由
            return $this->getDefaultRoute();
        } else if (is_array($data)) {
            // 转过来是数组
            if (!isset($data['route'])) {
                return $this->getDefaultRoute();
            }
            return [
                'controller' => isset($data['route']['controller']) ?? 'index',
                'action' => isset($data['route']['action']) ?? 'index',
            ];
        } else {
            throw new Exception('route error.');
        }
    }
    /**
     * [resolveData description]
     * @method   resolveData
     * @DateTime 2017-08-22T16:54:52+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    protected function resolveData($data)
    {
        if (is_array($data)) {
            // 转过来是数组
            if (!isset($data['data'])) {
                return [];
            }
            return $data['data'];
        } else {
            throw new Exception('data error.');
        }
    }
    /**
     * 解析参数
     * @method   resolveData
     * @DateTime 2017-08-22T16:52:37+0800
     * @param    [type]                   $data [description]
     * @return   [type]                         [description]
     */
    protected function resolve($data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        // 路由
        $this->route = $this->resolveRoute($data);

        // 参数
        $this->data = $this->resolveData($data);

    }
    /**
     * 获取默认路由
     * @method   getDefaultRoute
     * @DateTime 2017-08-22T16:39:35+0800
     * @return   [type]                   [description]
     */
    protected function getDefaultRoute()
    {
        return [
            'controller' => 'index',
            'action' => 'index',
        ];
    }
}
