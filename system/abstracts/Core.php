<?php
namespace luffyzhao\abstracts;

use luffyzhao\App;
use luffyzhao\Exception;
use luffyzhao\librarys\route\After;
use luffyzhao\librarys\route\Defer;
use luffyzhao\librarys\route\Tick;
use luffyzhao\db\Db;

abstract class Core
{
    protected $route = null;

    /**
     * Core constructor.
     * @param $route
     */
    function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * [task description]
     * @param  string $method [description]
     * @param  array  $params [description]
     * @return [type]         [description]
     */
    protected function task(string $method, array $params = [])
    {
        $data = [
            'data' => $params,
            'route' => $this->method($method),
        ];
        $this->getApp()->task(json_encode($data));
    }
    /**
     * 一次性定时器
     * @param  int    $afterTimeMs [description]
     * @return [type]              [description]
     */
    protected function after(string $method, int $afterTimeMs, array $data = [])
    {
        $this->getApp()->after($afterTimeMs, function () use ($method, $data) {
            $route = new After([
                'route' => $this->method($method),
                'data' => $data,
            ]);
            $route->run();
        });
    }
    /**
     * 定时器
     * @param  string $method      [description]
     * @param  int    $afterTimeMs [description]
     * @return [type]              [description]
     */
    protected function tick(string $method, int $afterTimeMs, array $data = [])
    {
        $this->getApp()->tick($afterTimeMs, function () use ($method, $data) {
            $route = new Tick([
                'route' => $this->method($method),
                'data' => $data,
            ]);
            $route->run();
        });
    }
    /**
     * 延后执行
     * @param  string $method [description]
     * @return [type]         [description]
     */
    protected function defer(string $method, array $data = [])
    {
        $this->getApp()->defer(function () use ($method, $data) {
            $route = new Defer([
                'route' => $this->method($method),
                'data' => $data,
            ]);
            $route->run();
        });
    }
    /**
     * method解析
     * @param  [type] $method [description]
     * @return [type]         [description]
     */
    protected function method($method)
    {
        if (strpos($method, '/') === false) {
            throw new Exception('task method error');
        }
        $route = explode('/', $method, 2);
        return [
            'controller' => $route[0],
            'action' => $route[1],
        ];
    }
    /**
     * 获取server
     * @return [type] [description]
     */
    protected function getApp()
    {
        return App::instance()->getServer();
    }

    /**
     * 获取数据curd
     */
    protected function getDb(){
        $databases = Config::get('databases');
        return Db::connect($databases);
    }
}
