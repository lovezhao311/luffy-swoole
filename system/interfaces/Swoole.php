<?php
namespace luffyzhao\interfaces;

use Swoole\Server;

interface Swoole
{
    /**
     * 设置swoole服务
     * @method   server
     * @DateTime 2017-08-25T11:49:42+0800
     * @return   [type]                   [description]
     */
    public function server();
    /**
     * 设置set
     * @method   serverSet
     * @DateTime 2017-08-25T11:49:53+0800
     * @return   [type]                   [description]
     */
    public function serverSet();
    /**
     * 服务server启动
     * @method   start
     * @DateTime 2017-08-25T14:01:52+0800
     * @return   [type]                   [description]
     */
    public function start();
}
