<?php
namespace luffyzhao\librarys\server;

use luffyzhao\abstracts\Swoole;
use luffyzhao\librarys\route\Http as HttpRoute;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Http extends Swoole
{
    /**
     * swoole配置
     * @var array
     */
    protected $config = [
        // worker进程数
        "worker_num" => 1,
        // 是否守护进程化
        "daemonize" => false,
        // worker进程的最大任务数
        "max_request" => 5000,
        // Task进程的数量
        "task_worker_num" => 1,
        // task进程的最大任务数
        "task_max_request" => 5000,
        // task进程与worker进程之间通信的方式
        "task_ipc_mode" => 2,
        // 消息队列的KEY
        "message_queue_key" => 'luffyzhao-queue',
        // 数据包分发策略
        "dispatch_mode" => 2,
        // 指定swoole错误日志文件
        "log_file" => 'swoole.log',
        // 日志等级
        "log_level" => 1
    ];

    /**
     * 设置server
     */
    public function server($host='127.0.0.1', $port=9501, $mode=SWOOLE_PROCESS, $sockType=SWOOLE_SOCK_TCP)
    {
        if (is_null($this->swoole)) {
            $this->swoole = new \Swoole\Http\Server($host, $port, $mode, $sockType);
        }
        return $this->swoole;
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
            $route = new HttpRoute($request);
            $result = $route->run();
        } catch (\Exception $e) {
            switch ($e->getCode()){
                case 404:
                    $response->status($e->getCode());
                    break;
                default:
                    $response->status(502);
            }
            $result = $e->getMessage();
        }
        unset($route);
        $response->end($result);
    }

}
