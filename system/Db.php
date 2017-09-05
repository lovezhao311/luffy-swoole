<?php
namespace luffyzhao;

/**
 *
 */
class Db
{
    protected static $instance = null;

    protected function __construct()
    {
        # code...
    }

    public static function instance()
    {
        if (self::$instance == null) {
            $databases = Config::get('databases');
            if (empty($databases['type'])) {
                throw new \InvalidArgumentException('Underfined db type');
            }
            $class = false !== strpos($databases['type'], '\\') ? $databases['type'] : '\\luffyzhao\\librarys\\databases\\' . ucwords($databases['type']);
            self::$instance = new $class($databases);
        }
        return self::$instance;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array([self::instance(), $name], $arguments);
    }

    protected function __clone()
    {

    }
}
