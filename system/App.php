<?php
namespace luffyzhao;

use luffyzhao\abstracts\Swoole;
use luffyzhao\Config;

class App
{
    /**
     * swoole 实例
     * @var null
     */
    protected static $server = null;
    /**
     * databases 实例
     * @var null
     */
    protected static $db = null;

    /**
     * 获取数据库
     * @return [type] [description]
     */
    public static function getDb()
    {
        if (self::$db == null) {
            $databases = Config::get('databases');
            if (empty($databases['type'])) {
                throw new \InvalidArgumentException('Underfined db type');
            }
            $class = false !== strpos($databases['type'], '\\') ? $databases['type'] : '\\luffyzhao\\librarys\\databases\\' . ucwords($databases['type']);
            self::$db = new $class($databases);
        }
        return self::$db;
    }
    /**
     * 设置swoole server
     * @param [type] $server [description]
     */
    public static function setServer($server)
    {
        self::$server = $server;
    }
    /**
     * 获取swoole server
     * @return [type] [description]
     */
    public static function getServer()
    {
        return self::$server;
    }
}
