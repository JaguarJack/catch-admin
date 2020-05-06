<?php

use catchAdmin\login\LoginLogListener;
use catchAdmin\permissions\OperateLogEvent;
use catcher\event\LoadModuleRoutes;

return [
    /**
     * set domain if you need
     *
     */
    'domain' => '',

    /**
     * 权限配置
     *
     */
    'permissions' => [
        /**
         * get 请求不验证
         */
        'is_allow_get' => true,
        
        /**
         * 超级管理员 ID
         *
         */
        'super_admin_id' => 1,
     ],
    /**
     *  auth 认证
     *
     */
  'auth' => [
      // 默认
      'default' => [
        'guard' => 'admin',
      ],
      // 门面设置
      'guards' => [
        // admin 认证
        'admin' => [
          'driver' => 'jwt',
          'provider' => 'admin_users',
        ],
      ],
      // 服务提供
      'providers' => [
        'admin_users' => [
          'driver' => 'orm',
          'model' =>  \catchAdmin\permissions\model\Users::class,
        ],
      ],
  ],

  /**
   * 自定义验证规则
   *
   */
  'validates' => [
    \catcher\validates\Sometimes::class,
  ],
    /**
     * 上传设置
     *
     */
  'upload' => [
      'image' => 'fileSize:' . 1024 * 1024 * 5 . '|fileExt:jpg,png,gif,jpeg',
      'file' => 'fileSize:' . 1024 * 1024 * 10 . '|fileExt:txt,pdf,xlsx,xls,html'
  ],
  /**
   * 路由中间件
   *
   */
  'route_middleware' => [
     \catchAdmin\permissions\AuthTokenMiddleware::class,
      \catchAdmin\permissions\RecordOperateMiddleware::class,
     \catchAdmin\permissions\PermissionsMiddleware::class,
  ],
  /**
   * 后台事件
   *
   */
  'events' => [
    // 登录日志
    'loginLog' => [
        \catchAdmin\login\LoginLogEvent::class,
    ],
    // 操作日志
    'operateLog' => [
      OperateLogEvent::class,
    ],
    // 路由加载
    'RouteLoaded' => [
      LoadModuleRoutes::class
    ],
  ],
];
