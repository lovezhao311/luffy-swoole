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
     * 配置
     * @var array
     */
    protected $config = [
        "listen" => '127.0.0.1',
        "port" => 9501,
        "set" => [
            "worker_num" => 8,
            "daemonize" => false,
            "max_request" => 0,
            "task_worker_num" => 4,
            "task_max_request" => 0,
            "dispatch_mode" => 2,
            "log_file" => 'swoole.log',
        ],
    ];

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
        if (isset($config['set'])) {
            $config['set'] = array_merge($this->config['set'], $config['set']);
        }
        $this->config = array_merge($this->config, $config);
    }
    /**
     * 启动server
     * @method   start
     * @DateTime 2017-08-25T12:16:50+0800
     * @return   [type]                   [description]
     */
    public function start()
    {
        $this->server->server($this->config['listen'], $this->config['port']);
        $this->server->serverSet($this->config['set']);
        $this->server->start();
    }
}
