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
namespace catchAdmin\sms;

use catchAdmin\sms\model\SmsConfig;
use catcher\exceptions\FailedException;
use Overtrue\EasySms\EasySms;
use think\helper\Str;

class Sms
{
    /**
     * timeout http 请求时间
     *
     * @var int
     */
    protected $timeout = 5;

    /**
     * 错误日志
     *
     * @var string
     */
    protected $errorLog;

    /**
     * 网关
     *
     * @var array
     */
    protected $gateways = [];

    /**
     * 配置
     *
     * @var array
     */
    protected $config = [];

    /**
     * 发送数据
     *
     * @var array
     */
    protected $sendData = [];

    /**
     * Sms constructor.
     * @param $config
     */
    public function __construct(array $config)
    {
        $config['timeout'] = $this->timeout;

        $config['gateways']['errorlog'] = runtime_path('log') . 'sms.log';

        $this->config = $config;
    }

    /**
     * 发送
     *
     * @time 2020年09月17日
     * @param string $phone
     * @param array $data
     * @return mixed
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     */
    public function send(string $phone, array $data)
    {
        try {
            $this->sendData['data'] = $data;

            return $this->easySms()
                ->send($phone, $this->sendData);
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }
    }


    /**
     * easy sms
     *
     * @time 2020年09月17日
     * @return EasySms
     */
    public function easySms()
    {
        return new EasySms($this->config);
    }


    /**
     * 内容
     *
     * @time 2020年09月17日
     * @param $content
     * @param string $key
     * @return $this
     */
    public function content($content, $key = 'content')
    {
        $this->sendData[$key] = $content;

        return $this;
    }

    /**
     * 模版
     *
     * @time 2020年09月17日
     * @param $template
     * @param string $key
     * @return $this
     */
    public function template($template, $key = 'template')
    {
        $this->sendData[$key] = $template;

        return $this;
    }

    /**
     * 超时间时间 s
     *
     * @time 2020年09月17日
     * @param int $timeout
     * @return $this
     */
    public function timeout(int $timeout)
    {
        $this->config['timeout'] = $timeout;

        return $this;
    }


    /**
     * 记录记录地址
     *
     * @time 2020年09月17日
     * @param string $log
     * @return $this
     */
    public function errorLog(string $log)
    {
        $this->config['gateways']['errorlog'] = $log;

        return $this;
    }

    /**
     * gateways config
     *
     * @time 2020年09月17日
     * @param $gateways
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array
     */
    protected static function getGatewaysConfig($gateways)
    {
        $gatewaysConfig = [];

        $smsConfig = new SmsConfig();

        foreach ($gateways as $gate) {
            $c = $smsConfig->findByName($gate);

            if ($c) {
                $c->hasConfig()
                    ->select()
                    ->each(function ($item) use (&$gatewaysConfig, $gate){
                         $gatewaysConfig[$gate][$item['key']] = $item['value'];
                    });
            }

        }

        return $gatewaysConfig;
    }

    /**
     * sms
     *
     * @time 2020年09月17日
     * @param $method
     * @param $arg
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return Sms
     */
    public static function __callStatic($method, $arg)
    {
        $gateways = Str::snake($method);

        if (Str::contains($gateways, '_')) {
            $gateways = explode('_', $gateways);
        } else {
            $gateways = [$gateways];
        }

        $config = [
            'default' => [
                'gateways' => $gateways,
            ],

            'gateways' => static::getGatewaysConfig($gateways)
        ];


        return new self($config);
    }
}