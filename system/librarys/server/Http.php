<?php
namespace luffyzhao\librarys\server;

use luffyzhao\abstracts\Swoole;
use luffyzhao\librarys\route\Http as HttpRoute;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Http extends Swoole
{
    /**
     * 设置server
     */
    protected function setSwoole()
    {
        $this->swoole = new \Swoole\Http\Server('127.0.0.1', 9501);
        $this->swoole->set([
            'daemonize' => false,
            'worker_num' => 4,
            'max_request' => 1000,
            'task_worker_num' => 2,
            'task_max_request' => 1000,
            'dispatch_mode' => 2,
            'log_file' => './swoole.log',
        ]);
    }

    /**
     * 注册Server的事件通用回调函数
     * @return [type] [description]
     */
    protected function initOn()
    {
        parent::initOn();
        $this->swoole->on('request', [$this, 'onRequest']);
    }
    /**
     * httpt请求时
     * @param  Request  $request  http请求对
     * @param  Response $response response
     * @return viod
     */
    public function onRequest(Request $request, Response $response)
    {
        try {
            $route = HttpRoute::instance($request);
            $result = $this->routeRun($route);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        $response->end($result);
    }
}
