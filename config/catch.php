<?php
return [
    /**
     * set domain if you need
     *
     */
    'domain' => '',

  /**
   * 权限不验证 get 请求
   *
   */
    'is_allow_get' => true,

  /**
   * auth 认证
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
          'model' =>  \catchAdmin\user\model\Users::class,
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
];
