## 域名管理
- 阿里云（主支持）
- 腾讯云（需要做适配）

#### 配置
首先在 .env 文件设置
```
[ALIYUN]
ACCESS_KEY=
ACCESS_SECRET=

[QCLOUD]
ACCESS_KEY=
ACCESS_SECRET=
```
也可以在 config.php 文件配置
```
'domains' => [
    // 默认阿里云
    'default' => 'aliyun',

    /**
     * 阿里云配置
     *
     */
    'aliyun' => [
        'api_domain' => 'http://alidns.aliyuncs.com',

        'access_key' => Env::get('aliyun.access_key', ''),

        'access_secret' =>  Env::get('aliyun.access_secret', ''),
    ],

    /**
     * 腾讯云配置
     *
     */
    'qcloud' => [
        'api_domain' => 'cns.api.qcloud.com',

        'access_key' => Env::get('qcloud.access_key', ''),

        'access_secret' => Env::get('qcloud.access_secret', ''),
    ]
]
```