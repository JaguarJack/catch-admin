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
namespace catchAdmin\wechat\library\messages\events;

use catchAdmin\wechat\library\messages\Message;
use catchAdmin\wechat\model\WechatUsers;

/**
 * 取消订阅事件
 *
 * Class Unsubscribe
 * @package catchAdmin\wechat\library\messages\events
 */
class Unsubscribe extends Message
{
    public function reply()
    {
        // TODO: Implement reply() method.
        WechatUsers::where('openid', $this->fromUserName())->find()->delete();
    }
}