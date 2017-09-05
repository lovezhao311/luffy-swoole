<?php
namespace luffyzhao\abstracts;

use luffyzhao\App;
use luffyzhao\Debug;
use luffyzhao\Error;
use luffyzhao\librarys\route\Task;
use Swoole\Server;

abstract class Swoole
{
    protected $swoole = null;

    protected $processTitle = '';
    /**
     * swoole配置
     * @var array
     */
    protected $config = [];

    /**
     * 设置
     * @method   serverSet
     * @DateTime 2017-08-25T11:47:48+0800
     * @return   [type]                   [description]
     */
    public function serverSet(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }
    /**
     * 启动server
     * @method   start
     * @DateTime 2017-08-25T12:10:41+0800
     * @return   [type]                   [description]
     */
    public function start($host = '127.0.0.1', $port = 9501, $mode = SWOOLE_PROCESS, $sockType = SWOOLE_SOCK_TCP)
    {
        $this->server($host, $port, $mode, $sockType);
        $this->swoole->set($this->config);
        $this->on();
        $this->swoole->start();
    }
    /**
     * 缓冲区内容输出
     * @return [type] [description]
     */
    protected function obShow()
    {
        $result = ob_get_contents();
        ob_end_clean();
        echo "\n" . $result . "\n";
    }
    /**
     * Server启动时调用
     * @method   onStart
     * @DateTime 2017-08-21T17:34:05+0800
     * @param    Server                   $server Server对象
     * @return   viod
     */
    public function onStart(Server $server)
    {
        Debug::info('start:luffyzhao-' . $this->processTitle . '-master');
        $this->setProcessTitle('luffyzhao-' . $this->processTitle . '-master');
    }
    /**
     * Server结束时调用
     * @method   onShutdown
     * @DateTime 2017-08-21T17:35:54+0800
     * @param    Server                   $server Server对象
     * @return   viod
     */
    public function onShutdown(Server $server)
    {
        Debug::info('shutdown:luffyzhao-' . $this->processTitle . '-master');
    }
    /**
     * 子进程启动时调用
     * @method   onWorkerStart
     * @DateTime 2017-08-21T17:36:42+0800
     * @param    Server                   $server   Server对象
     * @param    int                      $workerId 子进程ID
     * @return   viod
     */
    public function onWorkerStart(Server $server, int $workerId)
    {
        // 加载vendor
        require realpath(dirname(__FILE__)) . '/../../vendor/autoload.php';
        // 注册错误
        Error::register();
        // 存储server到App
        App::setServer($server);
        if ($server->taskworker) {
            Debug::info('start:task,id:' . $workerId);
            $this->setProcessTitle("luffyzhao-{$this->processTitle}-task-{$workerId}");
        } else {
            Debug::info('start:worker,id:' . $workerId);
            $this->setProcessTitle("luffyzhao-{$this->processTitle}-worker-{$workerId}");
        }
    }
    /**
     *
     * @param string $value [description]
     */
    protected function setProcessTitle($title)
    {
        if (function_exists('cli_set_process_title')) {
            @cli_set_process_title($title);
        } else {
            @swoole_set_process_name($title);
        }
    }
    /**
     * 子进程结束时调用
     * @method   onWorkerStop
     * @DateTime 2017-08-21T17:37:47+0800
     * @param    Server                   $server   Server对象
     * @param    int                      $workerId 子进程ID
     * @return   viod
     */
    public function onWorkerStop(Server $server, int $workerId)
    {
        // 暂时什么都不做。。
    }
    /**
     * 定时器触发时调用
     * $server->addtimer()来添加的
     * @method   onTimer
     * @DateTime 2017-08-21T17:40:43+0800
     * @param    Server                   $server   Server对象
     * @param    int                      $interval 间隔
     * @return   viod
     */
    public function onTimer(Server $server, int $interval)
    {
        Debug::info('timer :' . $interval);
    }
    /**
     * 接收到UDP数据包时调用
     * @method   onPacket
     * @DateTime 2017-08-21T17:46:17+0800
     * @param    Server                   $server     Server对象
     * @param    string                   $data       收到的数据内容，可能是文本或者二进制内容
     * @param    array                    $clientInfo 客户端信息包括address/port/server_socket3项数据
     * @return   viod
     */
    public function onPacket(Server $server, string $data, array $clientInfo)
    {

    }
    /**
     * TCP客户端连接关闭后调用。
     * @method   onClose
     * @DateTime 2017-08-21T17:47:29+0800
     * @param    Server                   $server    Server对象
     * @param    int                      $fd        连接标识
     * @param    int                      $fromId    ID线程
     * @return   viod
     */
    public function onClose(Server $server, int $fd, int $fromId)
    {

    }
    /**
     * 当缓存区达到最高水位时触发此事件
     * @method   onBufferFull
     * @DateTime 2017-08-21T17:48:40+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fd     连接标识
     * @return   viod
     */
    public function onBufferFull(Server $server, int $fd)
    {
        Debug::info('the send queue is full!');
    }
    /**
     * 当缓存区低于最低水位线时触发此事件
     * @method   onBufferEmpty
     * @DateTime 2017-08-21T17:49:50+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fd     连接标识
     * @return   viod
     */
    public function onBufferEmpty(Server $server, int $fd)
    {
        // 暂时什么都不做。。
    }
    /**
     * 在task_worker进程内被调用。worker进程可以使用swoole_server_task函数向task_worker进程投递新的任务。
     * @method   onTask
     * @DateTime 2017-08-21T17:50:20+0800
     * @param    Server                   $server Server对象
     * @param    int                      $taskId      任务ID，由swoole扩展内自动生成，用于区分不同的任务。$taskId和$workerId组合起来才是全局唯一的
     * @param    int                      $workerId    子进程
     * @param    string                   $data        任务的内容
     * @return   viod
     */
    public function onTask(Server $server, int $taskId, int $workerId, string $data)
    {
        ob_start();
        Debug::info('task start: taskId:' . $taskId . ' workerId:' . $workerId . ' data:' . $data);
        try {
            $route = new Task($data);
            $route->run();
            $server->finish();
        } catch (\Exception $e) {
            Debug::info('task error:' . $e->getMessage());
        }
        $this->obShow();
    }
    /**
     * 当worker进程投递的任务在taskWorker中完成
     * @method   onFinish
     * @DateTime 2017-08-21T17:56:15+0800
     * @param    Server                   $server Server对象
     * @param    int                      $taskId 任务ID
     * @param    string                   $data   任务的内容
     * @return   viod
     */
    public function onFinish(Server $server, int $taskId, string $data)
    {
        Debug::info('task finish: taskId:' . $taskId . ' workerId:' . $server->worker_id . ' data:' . $data);
    }
    /**
     * 当工作进程收到由sendMessage发送的管道消息时会触发onPipeMessage事件
     * @method   onPipeMessage
     * @DateTime 2017-08-21T17:58:06+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fromWorkerId 任务ID
     * @param    string                   $message      任务的内容
     * @return   viod
     */
    public function onPipeMessage(Server $server, int $fromWorkerId, string $message)
    {
        // 暂时什么都不做。。
    }
    /**
     * 当worker/task_worker进程发生异常后会在Manager进程内回调此函数
     * @method   onWorkerError
     * @DateTime 2017-08-21T17:58:58+0800
     * @param    Server                   $server    Server对象
     * @param    int                      $workerId  workerId
     * @param    int                      $workerPid workerPid
     * @param    int                      $exitCode  退出的状态码，范围是 1 ～255
     * @return   viod
     */
    public function onWorkerError(Server $server, int $workerId, int $workerPid, int $exitCode)
    {
        Debug::info(' error, exit code ' . $exitCode . ',id ' . $workerId . ',Pid ' . $workerPid);
    }
    /**
     * 管理进程启动时调用
     * @method   onManagerStart
     * @DateTime 2017-08-21T17:39:02+0800
     * @param    Server                   $server Server对象
     * @return   viod
     */
    public function onManagerStart(Server $server)
    {
        Debug::info('start:luffyzhao manager');
        $this->setProcessTitle("luffyzhao-" . $this->processTitle . '-manager');
    }
    /**
     * 管理进程停止时调用
     * @method   onManagerStop
     * @DateTime 2017-08-21T17:39:38+0800
     * @param    Server                   $server $server Server对象
     * @return   viod
     */
    public function onManagerStop(Server $server)
    {
        Debug::info('stop:luffyzhao manager');
    }
}
