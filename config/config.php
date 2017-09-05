<?php
use luffyzhao\App;

return [
    'port_list' => [
        9501 => [
            'server' => App::WEBSOCKET,
            'host' => '0.0.0.0',
        ],
        9502 => [
            'server' => App::HTTP,
            'host' => '0.0.0.0',
        ],
        9503 => [
            'server' => App::HTTP,
            'host' => '127.0.0.1',
        ],
        9504 => [
            'server' => App::TCP,
            'host' => '0.0.0.0',
            'sock_type' => SWOOLE_TCP,
        ],
        9505 => [
            'server' => App::TCP,
            'host' => '0.0.0.0',
            'sock_type' => SWOOLE_UDP,
        ],
    ],
    //
    'master_port' => 9501,
    //
    "set" => [
        // worker进程数
        "worker_num" => 4,
        // 是否守护进程化
        "daemonize" => false,
        // worker进程的最大任务数
        "max_request" => 5000,
        // Task进程的数量
        "task_worker_num" => 4,
        // task进程的最大任务数
        "task_max_request" => 5000,
        // task进程与worker进程之间通信的方式
        "task_ipc_mode" => 1,
        // 消息队列的KEY
        "message_queue_key" => 'luffyzhao-queue',
        // 数据包分发策略
        "dispatch_mode" => 2,
        // 指定swoole错误日志文件
        "log_file" => '../logs/luffyzhao-swoole-http.log',
        // 日志等级
        "log_level" => 1,
    ],
];
