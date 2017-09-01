<?php
namespace luffyzhao\abstracts;

use luffyzhao\Debug;
use luffyzhao\Exception;
use PDO;

/**
 * Class Connection
 * @package luffyzhao
 *
 */
abstract class Databases
{
    /**
     * 当前连接实例
     * @var null
     */
    protected $linkID = null;
    /**
     * 当前写实例
     * @var null
     */
    protected $linkWrite = null;
    /**
     * 当前读实例
     * @var null
     */
    protected $linkRead = null;

    // 数据库连接参数配置
    protected $config = [
        // 数据库类型
        'type' => '',
        // 服务器地址
        'hostname' => '',
        // 数据库名
        'database' => '',
        // 用户名
        'username' => '',
        // 密码
        'password' => '',
        // 端口
        'hostport' => '',
        // 连接dsn
        'dsn' => '',
        // 数据库编码默认采用utf8
        'charset' => 'utf8',
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy' => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate' => false,
        // 读写分离后 主服务器数量
        'master_num' => 1,
        // 指定从服务器序号
        'slave_no' => '',
        // 数据返回类型
        'result_type' => PDO::FETCH_ASSOC,
        // debug
        'debug' => false,
    ];

    // PDO连接参数
    protected $params = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        // PDO::ATTR_PERSISTENT => true,
    ];

    /**
     * 构造函数 读取数据库配置信息
     * @access public
     * @param array $config 数据库配置数组
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->config = array_merge($this->config, $config);
        }
    }

    /**
     * 连接数据库方法
     * @access public
     * @param array         $config 连接参数
     * @param integer       $linkNum 连接序号
     * @return PDO
     * @throws PDOException
     */
    public function connect(array $config = [], $linkNum = 0, $autoConnection = false)
    {
        if (!isset($this->links[$linkNum])) {
            if (!$config) {
                $config = $this->config;
            } else {
                $config = array_merge($this->config, $config);
            }

            try {
                if (empty($config['dsn'])) {
                    $config['dsn'] = $this->parseDsn($config);
                }
                $this->links[$linkNum] = new PDO($config['dsn'], $config['username'], $config['password'], $this->params);

                $this->debug("dsn:{$config['dsn']}, username:{$config['username']}, password:{$config['password']}");

            } catch (\PDOException $e) {
                throw $e;
            }
        }
        return $this->links[$linkNum];
    }

    /**
     * 执行查询 返回数据集
     * @param  [type]  $sql    $sql sql指令
     * @param array         $bind 参数绑定
     * @param  boolean $master $master 是否在主服务器读操作
     * @return mixed
     * @throws PDOException
     */
    public function query($sql, $bind = [], $master = false)
    {
        $this->initConnect($master);
        if (!$this->linkID) {
            return false;
        }
        try {
            $pdo = $this->linkID->prepare($sql);
            // 是否为存储过程调用
            $procedure = in_array(strtolower(substr(trim($sql), 0, 4)), ['call', 'exec']);
            // 参数绑定
            if ($procedure) {
                $this->bindParam($pdo, $bind);
            } else {
                $this->bindValue($pdo, $bind);
            }

            $this->debug($this->getRealSql($sql, $bind));

            $pdo->execute();
            return $this->getResult($pdo);
        } catch (\PDOException $e) {
            if ($this->isBreak($e)) {
                return $this->close()->query($sql, $bind, $master);
            }
            throw new Exception($e->getMessage() . $this->getRealSql($sql, $bind));
        }
    }

    /**
     * 执行语句
     * @access public
     * @param string        $sql sql指令
     * @param array         $bind 参数绑定
     * @return int
     * @throws SqlException
     * @throws PDOException
     */
    public function execute($sql, $bind = [])
    {
        $this->initConnect(true);
        if (!$this->linkID) {
            return false;
        }

        try {
            // 预处理
            $pdo = $this->linkID->prepare($sql);
            // 是否为存储过程调用
            $procedure = in_array(strtolower(substr(trim($sql), 0, 4)), ['call', 'exec']);
            // 参数绑定
            if ($procedure) {
                $this->bindParam($pdo, $bind);
            } else {
                $this->bindValue($pdo, $bind);
            }

            $this->debug($this->getRealSql($sql, $bind));
            // 执行语句
            $pdo->execute();
            return $pdo->rowCount();
        } catch (\PDOException $e) {
            if ($this->isBreak($e)) {
                return $this->close()->execute($sql, $bind);
            }
            throw new Exception($e->getMessage() . $this->getRealSql($sql, $bind));
        }
    }

    /**
     * 启动事务
     * @access public
     * @return bool|mixed
     * @throws \Exception
     */
    public function startTrans()
    {
        $this->initConnect(true);
        if (!$this->linkID) {
            return false;
        }
        try {
            $this->linkID->beginTransaction();
            // debug
            $this->debug('begin');
        } catch (\PDOException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 用于非自动提交状态下面的查询提交
     * @access public
     * @return void
     * @throws PDOException
     */
    public function commit()
    {
        $this->initConnect(true);
        if (!$this->linkID) {
            return false;
        }
        $this->linkID->commit();
        $this->debug('commit');
    }

    /**
     * 事务回滚
     * @access public
     * @return void
     * @throws PDOException
     */
    public function rollback()
    {
        $this->initConnect(true);
        if (!$this->linkID) {
            return false;
        }
        $this->linkID->rollBack();
        $this->debug('rollBack');
    }

    /**
     * 获取最近插入的ID
     * @access public
     * @param string  $sequence     自增序列名
     * @return string
     */
    public function getLastInsID($sequence = null)
    {
        return $this->linkID->lastInsertId($sequence);
    }

    /**
     * 根据参数绑定组装最终的SQL语句 便于调试
     * @access public
     * @param string    $sql 带参数绑定的sql语句
     * @param array     $bind 参数绑定列表
     * @return string
     */
    public function getRealSql($sql, array $bind = [])
    {
        foreach ($bind as $key => $val) {
            $value = is_array($val) ? $val[0] : $val;
            $type = is_array($val) ? $val[1] : PDO::PARAM_STR;
            if (PDO::PARAM_STR == $type) {
                $value = $this->quote($value);
            } elseif (PDO::PARAM_INT == $type) {
                $value = (float) $value;
            }
            // 判断占位符
            $sql = is_numeric($key) ?
            substr_replace($sql, $value, strpos($sql, '?'), 1) :
            str_replace(
                [':' . $key . ')', ':' . $key . ',', ':' . $key . ' '],
                [$value . ')', $value . ',', $value . ' '],
                $sql . ' ');
        }
        return rtrim($sql);
    }
    /**
     * 是否断线
     * @param \PDOException  $e 异常对象
     * @return boolean
     */
    protected function isBreak($e)
    {
        return false;
    }
    /**
     * 获得数据集数组
     * @access protected
     * @param bool   $pdo      PDOStatement
     * @param bool   $procedure 是否存储过程
     * @return array
     */
    protected function getResult($pdo, $procedure = false)
    {
        if ($procedure) {
            // 存储过程返回结果
            return $this->procedure($pdo);
        }
        $result = $pdo->fetchAll($this->config['result_type']);
        return $result;
    }

    /**
     * 获得存储过程数据集
     * @access protected
     * @return array
     */
    protected function procedure($pdo)
    {
        $item = [];
        do {
            $result = $this->getResult($pdo);
            if ($result) {
                $item[] = $result;
            }
        } while ($pdo->nextRowset());
        return $item;
    }

    /**
     * debug
     * @param $messgaes
     */
    protected function debug($messgaes)
    {
        // debug
        if ($this->config['debug'] instanceof \Closure) {
            $this->config['debug']($messgaes);
        }
    }
    /**
     * 参数绑定
     * 支持 ['name'=>'value','id'=>123] 对应命名占位符
     * 或者 ['value',123] 对应问号占位符
     * @access public
     * @param array $bind 要绑定的参数列表
     * @return void
     * @throws SqlException
     */
    protected function bindValue($pdo, array $bind = [])
    {
        foreach ($bind as $key => $val) {
            // 占位符
            $param = is_numeric($key) ? $key + 1 : ':' . $key;
            if (is_array($val)) {
                if (PDO::PARAM_INT == $val[1] && '' === $val[0]) {
                    $val[0] = 0;
                }
                $result = $pdo->bindValue($param, $val[0], $val[1]);
            } else {
                $result = $pdo->bindValue($param, $val);
            }
            if (!$result) {
                throw new Exception("Error occurred  when binding parameters '{$param}'");
            }
        }
    }

    /**
     * 存储过程的输入输出参数绑定
     * @access public
     * @param array $bind 要绑定的参数列表
     * @return void
     * @throws SqlException
     */
    protected function bindParam($pdo, $bind)
    {
        foreach ($bind as $key => $val) {
            $param = is_numeric($key) ? $key + 1 : ':' . $key;
            if (is_array($val)) {
                array_unshift($val, $param);
                $result = call_user_func_array([$pdo, 'bindParam'], $val);
            } else {
                $result = $pdo->bindValue($param, $val);
            }
            if (!$result) {
                $param = array_shift($val);
                throw new Exception("Error occurred  when binding parameters '{$param}'");
            }
        }
    }

    /**
     * SQL指令安全过滤
     * @access public
     * @param string $str SQL字符串
     * @param bool   $master 是否主库查询
     * @return string
     */
    public function quote($str, $master = true)
    {
        $this->initConnect($master);
        return $this->linkID ? $this->linkID->quote($str) : $str;
    }

    /**
     * 初始化数据库连接
     * @access protected
     * @param boolean $master 是否主服务器
     * @return void
     */
    protected function initConnect($master = true)
    {
        if (!empty($this->config['deploy'])) {
            // 采用分布式数据库
            if ($master) {
                if (!$this->linkWrite) {
                    $this->linkWrite = $this->multiConnect(true);
                }
                $this->linkID = $this->linkWrite;
            } else {
                if (!$this->linkRead) {
                    $this->linkRead = $this->multiConnect(false);
                }
                $this->linkID = $this->linkRead;
            }
        } elseif (!$this->linkID) {
            // 默认单数据库
            $this->linkID = $this->connect();
        }
    }

    /**
     * 连接分布式服务器
     * @access protected
     * @param boolean $master 主服务器
     * @return PDO
     */
    protected function multiConnect($master = false)
    {
        $_config = [];
        // 分布式数据库配置解析
        foreach (['username', 'password', 'hostname', 'hostport', 'database', 'dsn', 'charset'] as $name) {
            $_config[$name] = explode(',', $this->config[$name]);
        }

        // 主服务器序号
        $m = floor(mt_rand(0, $this->config['master_num'] - 1));

        if ($this->config['rw_separate']) {
            // 主从式采用读写分离
            if ($master) // 主服务器写入
            {
                $r = $m;
            } elseif (is_numeric($this->config['slave_no'])) {
                // 指定服务器读
                $r = $this->config['slave_no'];
            } else {
                // 读操作连接从服务器 每次随机连接的数据库
                $r = floor(mt_rand($this->config['master_num'], count($_config['hostname']) - 1));
            }
        } else {
            // 读写操作不区分服务器 每次随机连接的数据库
            $r = floor(mt_rand(0, count($_config['hostname']) - 1));
        }
        $dbMaster = false;
        if ($m != $r) {
            $dbMaster = [];
            foreach (['username', 'password', 'hostname', 'hostport', 'database', 'dsn', 'charset'] as $name) {
                $dbMaster[$name] = isset($_config[$name][$m]) ? $_config[$name][$m] : $_config[$name][0];
            }
        }
        $dbConfig = [];
        foreach (['username', 'password', 'hostname', 'hostport', 'database', 'dsn', 'charset'] as $name) {
            $dbConfig[$name] = isset($_config[$name][$r]) ? $_config[$name][$r] : $_config[$name][0];
        }
        return $this->connect($dbConfig, $r, $r == $m ? false : $dbMaster);
    }

    /**
     * 关闭数据库（或者重新连接）
     * @access public
     * @return $this
     */
    public function close()
    {
        $this->linkID = null;
        $this->linkWrite = null;
        $this->linkRead = null;
        $this->links = [];
        return $this;
    }

    /**
     * 解析pdo连接的dsn信息
     * @access protected
     * @param array $config 连接信息
     * @return string
     */
    abstract protected function parseDsn($config);
    /**
     * 析构方法
     */
    public function __destruct()
    {
        // 关闭连接
        $this->close();
    }
}
