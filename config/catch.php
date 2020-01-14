<?php
return [
    /**
     * set domain if you need
     *
     */
    'domain' => '',

    /**
     * set error page
     */
    'error' => root_path('catch/index/view/')  . 'error.html',


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

  'validates' => [
    \catcher\validates\Sometimes::class,
  ],
];
