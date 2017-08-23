<?php
namespace luffyzhao;

use luffySwoole\abstracts\Swoole;

class Server
{
    /**
     * @var object 对象实例
     */
    protected static $instance;
    /**
     * 实例
     * @var null
     */
    protected static $swoole = null;

    /**
     * 初始化
     * @access public
     * @param array $data 参数
     * @return luffyzhao\librarys\route\Task
     */
    public static function instance($class)
    {

        if (!isset(self::$instance[$class])) {
            self::$instance[$class] = new static($class);
        }
        return self::$instance[$class];
    }
    /**
     * 构造
     * @param Swoole $swoole [description]
     */
    protected function __construct($class)
    {
        self::$swoole = new $class;
    }
}
