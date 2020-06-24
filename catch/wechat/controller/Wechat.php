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
use think\facade\Log;
use think\Request;
use catcher\library\WeChat as WechatServer;

class Wechat extends CatchController
{
    public function index(Request $request)
    {
        $app =  WechatServer::officialAccount();
        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    file_put_contents(base_path() . DIRECTORY_SEPARATOR .'root.txt', json_encode((array)$message));
                    return '收到事件消息了吗😄';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    break;
                default:
            }

            return  '结束了';
        });


        $app->server->serve()->send();exit;
    }
}