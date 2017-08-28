<?php
namespace luffyzhao;

use luffyzhao\abstracts\Swoole;

class App
{
    /**
     * @var object 对象实例
     */
    protected static $instance;
    /**
     * 实例
     * @var null
     */
    protected $server = null;

    /**
     * 初始化
     * @access public
     * @param array $data 参数
     * @return luffyzhao\librarys\route\Task
     */
    public static function instance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    /**
     * 构造
     * @param Swoole $swoole [description]
     */
    protected function __construct()
    {}
    /**
     * 获取server
     * @return [type] [description]
     */
    public function getServer()
    {
        return $this->server->server();
    }
    /**
     * 设置server类型
     * @method   setServer
     * @DateTime 2017-08-25T12:14:29+0800
     * @param    Server                   $server [description]
     */
    public function setServer(Swoole $server)
    {
        $this->server = $server;
    }
    /**
     * 设置配置
     * @method   setConfig
     * @DateTime 2017-08-25T12:18:16+0800
     * @param    array                    $config [description]
     */
    public function setConfig(array $config)
    {
        $this->server->serverSet($config);
    }
    /**
     * 启动server
     * @method   start
     * @DateTime 2017-08-25T12:16:50+0800
     * @return   [type]                   [description]
     */
    public function start($host='127.0.0.1', $port=9501, $mode=SWOOLE_PROCESS, $sockType=SWOOLE_SOCK_TCP)
    {
        $this->server->start($host, $port, $mode, $sockType);
    }
}
