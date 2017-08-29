<?php
return [
    "host" => "127.0.0.1",
    "port" => 9501,
    "mode" => SWOOLE_PROCESS,
    "sockType" => SWOOLE_SOCK_TCP,
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
        "task_ipc_mode" => 2,
        // 消息队列的KEY
        "message_queue_key" => 'luffyzhao-queue',
        // 数据包分发策略
        "dispatch_mode" => 2,
        // 指定swoole错误日志文件
        "log_file" => 'swoole.log',
        // 日志等级
        "log_level" => 1,
    ],
];
