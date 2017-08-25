<?php
namespace luffyzhao\librarys\server;

use luffyzhao\abstracts\Swoole;
use luffyzhao\interfaces\Swoole as SwooleInterface;
use luffyzhao\librarys\route\Http as HttpRoute;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Http extends Swoole implements SwooleInterface
{
    /**
     * 设置server
     */
    public function server($host = '127.0.0.1', $port = 9501)
    {
        if (is_null($this->swoole)) {
            $this->swoole = new \Swoole\Http\Server($host, $port);
        }
        return $this->swoole;
    }
    /**
     * 设置
     * @method   serverSet
     * @DateTime 2017-08-25T11:47:48+0800
     * @return   [type]                   [description]
     */
    public function serverSet($config = [])
    {
        $this->swoole->set($config);
    }
    /**
     * 注册Server的事件通用回调函数
     * @return [type] [description]
     */
    protected function on()
    {
        $this->swoole->on('start', [$this, 'onStart']);
        $this->swoole->on('workerStart', [$this, 'onWorkerStart']);
        $this->swoole->on('workerStop', [$this, 'onWorkerStop']);
        $this->swoole->on('shutdown', [$this, 'onShutdown']);
        $this->swoole->on('workerError', [$this, 'onWorkerError']);
        $this->swoole->on('task', [$this, 'onTask']);
        $this->swoole->on('finish', [$this, 'onFinish']);
        //
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
