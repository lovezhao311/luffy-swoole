<?php
namespace luffyzhao\librarys\databases;

use luffyzhao\abstracts\Databases;

/**
 *
 */
class Mysql extends Databases
{
    /**
     * 解析pdo连接的dsn信息
     * @access protected
     * @param array $config 连接信息
     * @return string
     */
    protected function parseDsn($config)
    {
        $dsn = 'mysql:dbname=' . $config['database'] . ';host=' . $config['hostname'];
        if (!empty($config['hostport'])) {
            $dsn .= ';port=' . $config['hostport'];
        } elseif (!empty($config['socket'])) {
            $dsn .= ';unix_socket=' . $config['socket'];
        }
        if (!empty($config['charset'])) {
            $dsn .= ';charset=' . $config['charset'];
        }
        return $dsn;
    }

    /**
     * 是否断线
     * @access protected
     * @param \PDOException  $e 异常对象
     * @return bool
     */
    protected function isBreak($e)
    {
        if (false !== stripos($e->getMessage(), 'server has gone away')) {
            return true;
        }
        return false;
    }
}
