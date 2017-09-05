<?php
namespace luffyzhao;

use luffyzhao\Config;
use luffyzhao\librarys\swoole\Http;
use luffyzhao\librarys\swoole\Tcp;
use luffyzhao\librarys\swoole\Websocket;

class App
{
    const WEBSOCKET = 0;
    const HTTP = 1;
    const TCP = 2;

    protected static $masterServer = null;

    public static function run()
    {
        $config = Config::get('config');
        // portlist
        $portList = $config['port_list'];
        // maseter
        $master = $portList[$config['master_port']];

        switch ($master['server']) {
            case self::WEBSOCKET:
                self::$masterServer = new \Swoole\Websocket\Server($master['host'], $config['master_port']);
                break;
            case self::HTTP:
                self::$masterServer = new \Swoole\Http\Server($master['host'], $config['master_port']);
                break;
            case self::TCP:
                self::$masterServer = new \Swoole\Server($master['host'], $config['master_port'], SWOOLE_PROCESS, $master['sock_type']);
                break;
        }
        self::$masterServer->set($config['set']);
        self::build($master['server']);
        //del
        unset($portList[$config['master_port']]);

        if (count($portList) > 0) {
            $list = [];
            foreach ($portList as $port => $server) {
                switch ($server['server']) {
                    case self::WEBSOCKET:
                        $list[$port] = self::$masterServer->listen($server['host'], $port, SWOOLE_TCP);
                        if (isset($server['set']) && !empty($server['set'])) {
                            $list[$port]->set($server['set']);
                        }
                        $serverBuild = new Websocket();
                        $list[$port]->on('open', [$serverBuild, 'onOpen']);
                        $list[$port]->on('message', [$serverBuild, 'onMessage']);
                        $list[$port]->on('handShake', [$serverBuild, 'onHandShake']);
                        break;
                    case self::HTTP:
                        $list[$port] = self::$masterServer->listen($server['host'], $port, SWOOLE_TCP);
                        if (isset($server['set']) && !empty($server['set'])) {
                            $list[$port]->set($server['set']);
                        }
                        $serverBuild = new Http();
                        $list[$port]->on('request', [$serverBuild, 'onRequest']);
                        break;
                    case self::TCP:
                        $list[$port] = self::$masterServer->listen($server['host'], $port, $server['sock_type']);
                        if (isset($server['set']) && !empty($server['set'])) {
                            $list[$port]->set($server['set']);
                        }
                        $serverBuild = new Http();
                        $list[$port]->on('request', [$serverBuild, 'onRequest']);
                        break;
                }
            }
        }

        self::$masterServer->start();
    }

    public static function getServer()
    {
        return self::$masterServer;
    }
    /**
     * [__construct description]
     * @param [type] $config [description]
     */
    protected static function build($type)
    {
        switch ($type) {
            case self::WEBSOCKET:
                $serverBuild = new Websocket();
                break;
            case self::HTTP:
                $serverBuild = new HTTP();
                break;
            case self::TCP:
                $serverBuild = new TCP();
                break;
        }
        $serverBuild->build(self::$masterServer);
    }
}
