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
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return Factory::{$name}(\config('wechat.'. Str::snake($name)));
    }
}