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
use catcher\library\WeChat;

/**
 * 订阅事件
 *
 * Class Subscribe
 * @package catchAdmin\wechat\library\messages\events
 */
class Subscribe extends Message
{
    public function reply()
    {
        // TODO: Implement reply() method.
        $wechatUser = WechatUsers::onlyTrashed()->where('openid', $this->fromUserName())->find();
        if ($wechatUser) {
            return $wechatUser->restore();
        }

        $user = WeChat::officialAccount()->user->get($this->fromUserName());

        $user['avatar'] = $user['headimgurl'];
        $user['unionid'] = $user['unionid'] ?? '';
        $user['created_at'] = time();
        $user['updated_at'] = time();
        if (!empty($user['tagid_list'])) {
            $user['tagid_list'] = trim(implode(',', $user['tagid_list']), ',');
        }

        unset($user['headimgurl'], $user['qr_scene'], $user['qr_scene_str']);

        if (app(WechatUsers::class)->storeBy($user)) {
            return '谢谢你的关注';
        }

        return false;
    }

}