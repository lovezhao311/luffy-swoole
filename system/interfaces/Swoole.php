<?php
namespace luffyzhao\interfaces;

use Swoole\Server;

interface Swoole
{
    /**
     * Server启动时调用
     * @method   onStart
     * @DateTime 2017-08-21T17:34:05+0800
     * @param    Server                   $server Server对象
     * @return   viod
     */
    public function onStart(Server $server);
    /**
     * Server结束时调用
     * @method   onShutdown
     * @DateTime 2017-08-21T17:35:54+0800
     * @param    Server                   $server Server对象
     * @return   viod
     */
    public function onShutdown(Server $server);
    /**
     * 子进程启动时调用
     * @method   onWorkerStart
     * @DateTime 2017-08-21T17:36:42+0800
     * @param    Server                   $server   Server对象
     * @param    int                      $workerId 子进程ID
     * @return   viod
     */
    public function onWorkerStart(Server $server, int $workerId);
    /**
     * 子进程结束时调用
     * @method   onWorkerStop
     * @DateTime 2017-08-21T17:37:47+0800
     * @param    Server                   $server   Server对象
     * @param    int                      $workerId 子进程ID
     * @return   viod
     */
    public function onWorkerStop(Server $server, int $workerId);
    /**
     * 定时器触发时调用
     * $server->addtimer()来添加的
     * @method   onTimer
     * @DateTime 2017-08-21T17:40:43+0800
     * @param    Server                   $server   Server对象
     * @param    int                      $interval 间隔
     * @return   viod
     */
    public function onTimer(Server $server, int $interval);
    /**
     * 有新连接进入时调用
     * @method   onConnect
     * @DateTime 2017-08-21T17:41:48+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fd     连接标识
     * @param    int                      $fromId ID线程
     * @return   viod
     */
    public function onConnect(Server $server, int $fd, int $fromId);
    /**
     * 接收到数据时调用
     * @method   onReceive
     * @DateTime 2017-08-21T17:44:43+0800
     * @param    Server                   $server    Server对象
     * @param    int                      $fd        连接标识
     * @param    int                      $fromId    ID线程
     * @param    string                   $data      收到的数据内容，可能是文本或者二进制内容
     * @return   viod
     */
    public function onReceive(Server $server, int $fd, int $fromId, string $data);
    /**
     * 接收到UDP数据包时调用
     * @method   onPacket
     * @DateTime 2017-08-21T17:46:17+0800
     * @param    Server                   $server     Server对象
     * @param    string                   $data       收到的数据内容，可能是文本或者二进制内容
     * @param    array                    $clientInfo 客户端信息包括address/port/server_socket3项数据
     * @return   viod
     */
    public function onPacket(Server $server, string $data, array $clientInfo);
    /**
     * TCP客户端连接关闭后调用。
     * @method   onClose
     * @DateTime 2017-08-21T17:47:29+0800
     * @param    Server                   $server    Server对象
     * @param    int                      $fd        连接标识
     * @param    int                      $fromId    ID线程
     * @return   viod
     */
    public function onClose(Server $server, int $fd, int $fromId);
    /**
     * 当缓存区达到最高水位时触发此事件
     * @method   onBufferFull
     * @DateTime 2017-08-21T17:48:40+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fd     连接标识
     * @return   viod
     */
    public function onBufferFull(Server $server, int $fd);
    /**
     * 当缓存区低于最低水位线时触发此事件
     * @method   onBufferEmpty
     * @DateTime 2017-08-21T17:49:50+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fd     连接标识
     * @return   viod
     */
    public function onBufferEmpty(Server $server, int $fd);
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
    public function onTask(Server $server, int $taskId, int $workerId, string $data);
    /**
     * 当worker进程投递的任务在taskWorker中完成
     * @method   onFinish
     * @DateTime 2017-08-21T17:56:15+0800
     * @param    Server                   $server Server对象
     * @param    int                      $taskId 任务ID
     * @param    string                   $data   任务的内容
     * @return   viod
     */
    public function onFinish(Server $server, int $taskId, string $data);
    /**
     * 当工作进程收到由sendMessage发送的管道消息时会触发onPipeMessage事件
     * @method   onPipeMessage
     * @DateTime 2017-08-21T17:58:06+0800
     * @param    Server                   $server Server对象
     * @param    int                      $fromWorkerId 任务ID
     * @param    string                   $message      任务的内容
     * @return   viod
     */
    public function onPipeMessage(Server $server, int $fromWorkerId, string $message);
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
    public function onWorkerError(Server $server, int $workerId, int $workerPid, int $exitCode);
    /**
     * 管理进程启动时调用
     * @method   onManagerStart
     * @DateTime 2017-08-21T17:39:02+0800
     * @param    Server                   $server Server对象
     * @return   viod
     */
    public function onManagerStart(Server $server);
    /**
     * 管理进程停止时调用
     * @method   onManagerStop
     * @DateTime 2017-08-21T17:39:38+0800
     * @param    Server                   $server $server Server对象
     * @return   viod
     */
    public function onManagerStop(Server $server);

}
