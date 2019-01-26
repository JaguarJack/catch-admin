<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 14:25
 */
namespace thinking\socialite;

use thinking\socialite\provider\GithubProvider;
use thinking\socialite\provider\QqProvider;
use thinking\socialite\provider\WeiBoProvider;
use thinking\socialite\provider\WxProvider;

class Socialite
{
    /**
     * QQ 登录
     *
     * @time at 2018年12月28日
     * @return QqProvider
     */
    protected function qqDriver()
    {
        $config = config('socialite.qq');

        return new QqProvider(
            $config['app_id'], $config['app_secret'], $config['redirect_url'], $config['scope'] ?? ''
        );
    }

    /**
     * 微博 登录
     *
     * @time at 2018年12月28日
     * @return WeiBoProvider
     */
    protected function weiboDriver()
    {
        $config = config('socialite.weibo');

        return new WeiBoProvider(
            $config['app_id'], $config['app_secret'], $config['redirect_url'], $config['scope'] ?? ''
        );
    }

    /**
     * Github 登录
     *
     * @time at 2018年12月28日
     * @return GithubProvider
     */
    protected function githubDriver()
    {
        $config = config('socialite.github');

        return new GithubProvider(
            $config['app_id'], $config['app_secret'], $config['redirect_url'], $config['scope'] ?? ''
        );
    }

    /**
     * 微信 登录
     *
     * @time at 2018年12月28日
     * @return WxProvider
     */
    protected function wxDriver()
    {
        $config = config('socialite.wx');

        return new WxProvider(
            $config['app_id'], $config['app_secret'], $config['redirect_url'], $config['scope'] ?? ''
        );
    }

    /**
     * return provider
     *
     * @time at 2018年12月29日
     * @param $type
     * @return \thinking\socialite\provider\AbstractProvider
     */
    public function driver($type = null)
    {
        $driver = $this->createDriver($type);

        $driver->oauth();

        return $driver;
    }

    /**
     * create oauth provider
     *
     * @time at 2018年12月29日
     * @param $type
     * @return mixed
     */
    protected function createDriver($type)
    {
        $defaultDriver = config('socialite.default');

        $driver = $type ? : $defaultDriver;

        $function = $driver . 'Driver';

        return call_user_func([$this, $function]);
    }
}