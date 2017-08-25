<?php
namespace luffyzhao\librarys\server;

use luffyzhao\abstracts\Swoole;
use luffyzhao\Debug;
use luffyzhao\librarys\route\Tcp as TcpRoute;

class Tcp extends Swoole
{
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
    public function onReceive(Server $server, int $fd, int $fromId, string $data)
    {
        Debug::info('tcp start: fd:' . $fd . ' fromId:' . $fromId . ' data:' . $data);
        try {
            $route = TcpRoute::instance($data);
            $route->run();
            return true;
        } catch (\Exception $e) {
            Debug::info('task error:' . $e->getMessage());
        }
        $server->close($fd);
    }
}
