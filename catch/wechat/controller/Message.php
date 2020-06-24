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
namespace catchAdmin\wechat\controller;

use catcher\base\CatchController;
use catcher\library\WeChat;
use think\Request;

class Message extends CatchController
{
    public function done(Request $request)
    {
        if ($request->isPost()) {
            WeChat::officialAccount()->server->push(function ($message) {
                switch ($message['MsgType']) {
                    case 'subscribe':
                        return '收到事件消息';
                        break;
                    case 'unsubscribe':
                        return '收到文字消息';
                        break;
                    case 'image':
                    default:

                }

                return '';
            });
        }
    }
}