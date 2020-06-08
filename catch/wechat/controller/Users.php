<?php
/**
 * @filename Users.php
 * @date     2020/6/7
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catchAdmin\wechat\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\library\WeChat;

class Users extends CatchController
{
    public function index()
    {
        $response = WeChat::officialAccount()->base->getValidIps();

        return CatchResponse::success($response);
    }
}