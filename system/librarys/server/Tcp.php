<?php
namespace luffyzhao\librarys\server;

use luffyzhao\abstracts\Swoole;
use luffyzhao\Debug;
use luffyzhao\librarys\route\Tcp as TcpRoute;

class Tcp extends Swoole
{
    /**
     * swoole配置
     * @var array
     */
    protected $config = [
        // worker进程数
        "worker_num" => 8,
        // 是否守护进程化
        "daemonize" => false,
        // worker进程的最大任务数
        "max_request" => 2000,
        // Task进程的数量
        "task_worker_num" => 4,
        // task进程的最大任务数
        "task_max_request" => 2000,
        // task进程与worker进程之间通信的方式
        "task_ipc_mode" => 2,
        // 消息队列的KEY
        "message_queue_key" => 'luffyzhao-queue',
        // 数据包分发策略
        "dispatch_mode" => 2,
        // 指定swoole错误日志文件
        "log_file" => 'swoole.log',
        // 日志等级
        "log_level" => 0
    ];

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
        $this->swoole->on('receive',[$this,'onReceive']);
    }
    /**
     * 设置server
     */
    public function server($host='127.0.0.1', $port=9501, $mode=SWOOLE_PROCESS, $sockType=SWOOLE_SOCK_TCP)
    {
        if (is_null($this->swoole)) {
            $this->swoole = new \Swoole\Server($host, $port, $mode, $sockType);
        }
        return $this->swoole;
    }
    /**
     * 接收到数据时调用(除udp外)
     * @method   onReceive
     * @DateTime 2017-08-21T17:44:43+0800
     * @param    Server                   $server    Server对象
     * @param    int                      $fd        连接标识
     * @param    int                      $fromId    ID线程
     * @param    string                   $data      收到的数据内容，可能是文本或者二进制内容
     * @return   viod
     */
    public function onReceive(\Swoole\Server $server, int $fd, int $fromId, string $data)
    {
        Debug::info('tcp start: fd:' . $fd . ' fromId:' . $fromId . ' data:' . $data);
        try {
            $route = new TcpRoute($data);
            $message = $route->run();
        } catch (\Exception $e) {
            Debug::info('task error:' . $e->getMessage());
        }
        unset($route);
        $server->send($fd, $message);
        $server->close($fd);
    }
}
