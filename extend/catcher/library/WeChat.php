<?php
/**
 * @filename WeChat.php
 * @date     2020/6/7
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catcher\library;

use EasyWeChat\Factory;
use think\helper\Str;

/**
 *
 * @method static officialAccount()
 * @method static miniProgram()
 * @method static openPlatform()
 * @method static work()
 * @method static openWork()
 * @method static payment()
 *
 * Class WeChat
 * @package catcher\library
 */
class WeChat
{
    /**
     * 静态调用
     *
     * @time 2020年06月19日
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return Factory::{$name}(\config('wechat.'. Str::snake($name)));
    }

    /**
     * 动态调用
     *
     * @time 2020年06月19日
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return Factory::{$name}(\config('wechat.'. Str::snake($name)));
    }
}