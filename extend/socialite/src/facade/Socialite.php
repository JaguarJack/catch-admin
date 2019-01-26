<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/29
 * Time: 10:28
 */
namespace thinking\socialite\facade;

use think\Facade;

class Socialite extends Facade
{
    /**
     * @method  static  \thinking\socialite\Socialite driver($type) get provider
     *
     * @time at 2018年12月29日
     * @return string
     */
    public static function getFacadeClass()
    {
        return \thinking\socialite\Socialite::class;
    }
}