<?php
namespace luffyzhao\librarys\swoole;

use luffyzhao\abstracts\Swoole;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Websocket\Frame;
use Swoole\Websocket\Server;

/**
 *
 */
class WebSocket extends Swoole
{
    protected $processTitle = 'WebSocket';

    protected $on = [
        'start',
        'shutdown',
        'workerStart',
        'workerStop',
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
     * 当WebSocket客户端与服务器建立连接并完成握手后会回调此函数。
     * @param  Server $server  [description]
     * @param  [type] $request [description]
     * @return [type]          [description]
     */
    public function onOpen(Server $server, $request)
    {
        # code...
    }
    /**
     * 当服务器收到来自客户端的数据帧时会回调此函数。
     * @param  Server $server [description]
     * @param  [type] $frame  [description]
     * @return [type]         [description]
     */
    public function onMessage(Server $server, Frame $frame)
    {
        # code...
    }
    /**
     * WebSocket建立连接后进行握手。WebSocket服务器已经内置了handshake，如果用户希望自己进行握手处理
     * @param  Request  $request  [description]
     * @param  Response $response [description]
     * @return [type]             [description]
     */
    public function onHandShake(Request $request, Response $response)
    {
        # code...
    }
}
