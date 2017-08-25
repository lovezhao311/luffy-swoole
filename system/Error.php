<?php
namespace luffyzhao;

/**
 *
 */
class Error
{
    /**
     * 注册异常处理
     * @return void
     */
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }

    /**
     * 用户自定义的异常处理函数
     * @method   appException
     * @DateTime 2017-08-22T15:39:11+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public static function appException($e)
    {
        throw new Exception($e->getMessage(), $e->getCode());
    }
    /**
     * 用户自定义的错误处理函数
     * @method   appError
     * @DateTime 2017-08-22T15:41:48+0800
     * @param    int                      $errno  [description]
     * @param    string                   $errstr [description]
     * @return   [type]                           [description]
     */
    public static function appError(int $errno, string $errstr)
    {
        throw new Exception($errstr, $errno);
    }
    /**
     * php中止时调用
     * @method   appShutdown
     * @DateTime 2017-08-22T15:43:06+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public static function appShutdown()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管
            throw new Exception($error['message']);
        }
    }
    /**
     * 确定错误类型是否致命
     * @method   isFatal
     * @DateTime 2017-08-22T15:44:55+0800
     * @param    [type]                   $type 错误类型
     * @return   bool
     */
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }
}
