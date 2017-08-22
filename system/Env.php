<?php
namespace luffySwoole;

class Env
{
    /**
     * 加载.env环境变量
     * @param $path
     */
    public static function load($path = './')
    {
        $env = parse_ini_file($path . '.env', true);
        foreach ($env as $key => $val) {
            $name = strtoupper($key);
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $item = $name . '_' . strtoupper($k);
                    putenv("$item=$v");
                }
            } else {
                putenv("$name=$val");
            }
        }
    }
    /**
     * 获取环境变量值
     * @param string    $name 环境变量名（支持二级 .号分割）
     * @param string    $default  默认值
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        $result = getenv(strtoupper(str_replace('.', '_', $name)));
        if (false !== $result) {
            return $result;
        } else {
            return $default;
        }
    }
}
