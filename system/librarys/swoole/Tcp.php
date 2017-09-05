<?php
namespace luffyzhao\librarys\swoole;

use luffyzhao\abstracts\Swoole;
use luffyzhao\Debug;
use luffyzhao\librarys\route\Tcp as TcpRoute;
use Swoole\Server;

/**
 *
 */
class Tcp extends Swoole
{
    protected $processTitle = 'Tcp';

    protected $on = [
        'start',
        'shutdown',
        'workerStart',
        'workerStop',
        'timer',
        'connect',
        'close',
        'task',
        'finish',
        'pipeMessage',
        'workerError',
        'managerStart',
        'managerStop',
        'open',
        'message',
        'handShake',
    ];
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
    public function onReceive(Server $server, int $fd, int $fromId, string $data)
    {
        ob_start();
        Debug::info('tcp start: fd:' . $fd . ' fromId:' . $fromId . ' data:' . $data);
        try {
            $route = new TcpRoute($data);
            $message = $route->run();
        } catch (\Exception $e) {
            Debug::info('task error:' . $e->getMessage());
        }
        $server->send($fd, $message);
        $server->close($fd);
        $this->obShow();
    }
    /**
     * 接收到UDP数据包时回调此函数，发生在worker进程中
     * @param  Server $server     [description]
     * @param  string $data       [description]
     * @param  array  $clientInfo [description]
     * @return [type]             [description]
     */
    public function onPacket(Server $server, string $data, array $clientInfo)
    {

    }
}
