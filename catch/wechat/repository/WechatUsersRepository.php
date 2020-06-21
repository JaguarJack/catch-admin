<?php
/**
 * @filename  Users.php
 * @createdAt 2020/6/21
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catchAdmin\wechat\repository;

use catchAdmin\wechat\model\WechatUsers;
use catcher\base\CatchRepository;

class WechatUsersRepository extends CatchRepository
{

    public function __construct(WechatUsers $users)
    {
    }

    protected function model()
    {

    }
}