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

use catchAdmin\wechat\library\messages\Factory;
use catcher\base\CatchController;
use catcher\library\WeChat;
use think\Request;

class Message extends CatchController
{
    public function done(Request $request)
    {
        $app = WeChat::officialAccount();

        if ($request->isPost()) {
            $app->server->push(function ($message) {
                file_put_contents('root.txt', var_export($message, true), FILE_APPEND);
                if ($res = Factory::make($message)->reply()) {
                    return $res;
                }
            });
        }

        $app->server->serve()->send();exit;
    }
}