<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
return [
    /**
     *
     * 定时任务配置
     *
     */
    'crontab' => [
        /**
         * 存储目录
         */
        'store_path' => runtime_path('catch/crontab'),

        /**
         * 主进程 pid 存储
         */
        'master_pid_file' => runtime_path('catch/crontab') . 'master.pid',

        /**
         * 日志配置
         */
        'log' => [
            // 日志记录方式
            'type'           => 'File',
            // 日志保存目录
            'path'           => runtime_path('catch/schedule'),
            // 单文件日志写入
            'single'         => false,
            // 独立日志级别
            'apart_level'    => [],
            // 最大日志文件数量
            'max_files'      => 0,
            // 使用JSON格式记录
            'json'           => false,
            // 日志处理
            'processor'      => null,
            // 关闭通道日志写入
            'close'          => false,
            // 日志输出格式化
            'format'         => '[%s][%s] %s',
            // 是否实时写入
            'realtime_write' => false,
        ],

        /**
         * crontab 任务命名空间
         */
        'task_namespace' => '\\app\\task\\',
    ],
];