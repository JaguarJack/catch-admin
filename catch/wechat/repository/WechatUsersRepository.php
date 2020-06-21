<?php
/**
 * @filename  WechatUsersRepository.php
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
use catcher\library\WeChat;

class WechatUsersRepository extends CatchRepository
{
    protected $wechatUser;

    public function __construct(WechatUsers $users)
    {
        $this->wechatUser = $users;
    }

    /**
     * 模型
     *
     * @time 2020年06月21日
     * @return WechatUsers
     */
    protected function model()
    {
        return $this->wechatUser;
    }

    /**
     * 拉黑用户
     *
     * @time 2020年06月21日
     * @param $id
     * @return mixed
     */
    public function block($id)
    {
        $user = $this->wechatUser->findBy($id);

        $blockMethod = $user->block == WechatUsers::UNBLOCK ? 'block' : 'unblock';

        WeChat::throw(WeChat::officialAccount()->user->{$blockMethod}([$user->openid]));

        $user->block = $user->block == WechatUsers::BlOCK ? WechatUsers::UNBLOCK : WechatUsers::BlOCK;

        return $user->save();
    }

    /**
     * 粉丝备注
     *
     * @time 2020年06月21日
     * @param $id
     * @param string $remark
     * @return mixed
     */
    public function remark($id, string $remark)
    {
        $user = $this->wechatUser->findBy($id);

        WeChat::throw(WeChat::officialAccount()->user->remark($user->openid, $remark));

        $user->remark = $remark;

        return $user->save();
    }
}