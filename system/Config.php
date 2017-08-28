<?php
namespace luffyzhao;

class Config
{
    protected static $config = [];
    /**
     * 获取配置
     * @param string    $name 文件名.变量
     * @param string    $default  默认值
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        if(strpos('.', $name) === false){
            if(!isset(self::$config[$name])){
                self::$config[$name] = self::load($name);
            }
            return self::$config[$name];
        }else{
            list($name, $key) = explode('.', $name,2);
            if(!isset(self::$config[$name])){
                self::$config[$name] = self::load($name);
            }
            return self::$config[$name][$key] ?? $default;
        }
    }

    /**
     * 加载配置
     */
    protected static function load($name){
        $filename = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .'config'. DIRECTORY_SEPARATOR . strtolower($name) . '.php';
        if(file_exists($filename)){
            return include $filename;
        }
    }
}
