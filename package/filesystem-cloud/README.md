## Think-Filesystem-cloud

### 要求
   - php 7.1
   - topthink/framework 6.0.0
   
### 使用
```php
composer require jaguarjack/think-filesystem-cloud
```   

### 配置
config/filesystem.php
```php
"disks" => [
    // oss 配置
    'oss' => [
        'type'   => 'oss',
        'prefix' => '',
        'access_key' => '';
        'secret_key' => '';
        'end_point'  => ''; // ssl：https://iidestiny.com
        'bucket'     => '';
        'is_cname'   => true
    ],
    // 七牛配置
    'qiniu' => [
        'type'       => 'qiniu',
        'access_key' => '',
        'secret_key' => '',
        'bucket'     => '',
        'domain'     => '',
    ],
    // 腾讯云配置
    'qcloud' => [
        'type'        => 'qcloud',
        'region'      => '',
            'credentials' => [
                'appId'      => , // 域名中数字部分
                'secretId'   => '',
                'secretKey'  => '',
            ],
            'bucket'          => 'test',
            'timeout'         => 60,
            'connect_timeout' => 60,
            'cdn'             => '您的 CDN 域名',
            'scheme'          => 'https',
            'read_from_cdn'   => false,
    ]
```

### 感谢
   - [iiDestiny/flysystem-oss](https://github.com/iiDestiny/flysystem-oss)
   - [overtrue/flysystem-qiniu](https://github.com/overtrue/flysystem-qiniu)
   - [overtrue/flysystem-cos](https://github.com/overtrue/flysystem-cos)

### 协议
 MIT