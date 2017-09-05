<?php
namespace luffyzhao\librarys\route;

use luffyzhao\abstracts\Route;
use Swoole\Websocket\Frame;
use luffyzhao\Debug;

class Websocket extends Route
{
    protected $method = 'websocket';
    /**
     * 数据内容，可以是文本内容也可以是二进制数据，可以通过opcode的值来判断
     * @var null
     */
    protected $fd = null;
    /**
     * WebSocket的OpCode类型，可以参考WebSocket协议标准文档 
     * @var null
     */
    protected $opcode = null;
    /**
     * 表示数据帧是否完整，一个WebSocket请求可能会分成多个数据帧进行发送 
     * @var null
     */
    protected $finish = null;

    public function __construct(Frame $frame)
        $this->fd = $frame->fd;
        $this->opcode = $frame->opcode;
        $this->finish = $frame->finish;
        $this->resolve(json_decode($frame->data));
    }
    /**
     * [getFd description]
     * @return [type] [description]
     */
    public function getFd()
    {
        return $this->fd;
    }
    /**
     * [getOpcode description]
     * @return [type] [description]
     */
    public function getOpcode()
    {
        return $this->opcode;
    }
    /**
     * [getFinish description]
     * @return [type] [description]
     */
    public function getFinish()
    {
        return $this->finish;
    }
}
