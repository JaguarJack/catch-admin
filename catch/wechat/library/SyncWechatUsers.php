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
namespace catchAdmin\wechat\library;

use catchAdmin\wechat\model\WechatUsers;
use catcher\exceptions\FailedException;
use catcher\library\ProgressBar;
use catcher\library\WeChat;
use catcher\Utils;
use think\Db;

class SyncWechatUsers
{
    protected $officialAccount;

    public function start()
    {
        $this->officialAccount = WeChat::officialAccount();

        $latest = WechatUsers::order('subscribe_time')->find();

        if ($latest) {
            throw new FailedException('暂时无法增量同步');
        }

        $this->sync($latest ? $latest->openid : null);

        $this->syncTags();
    }

    protected function syncTags()
    {
        $users = WechatUsers::cursor();

        foreach ($users as $user) {
            if ($user->tag_list) {
                $tagIds = Utils::stringToArrayBy($user->tag_list);
                $relate = [];

                foreach ($tagIds as $id) {
                    $relate[] = [
                        'user_id' => $user->id,
                        'tag_id' => $id,
                    ];
                }

                Db::name('wechat_user_has_tags')->insertAll($relate);
            }
        }
    }
    /**
     * 同步
     *
     * @time 2020年06月20日
     * @param $nextOpenid
     * @return void
     */
    protected function sync($nextOpenid)
    {
        $userOpenids = $this->getWechatUserOpenids($nextOpenid);

        if ($userOpenids['next_openid']) {
            $this->getUsersBy($userOpenids['data']['openid']);
            $this->sync($userOpenids['next_openid']);
        } else {
            if ($userOpenids['count']) {
                $openids = $userOpenids['data']['openid'];
                $this->getUsersBy($openids);
            }
        }
    }

    /**
     * 获取用户
     *
     * @time 2020年06月20日
     * @param $openids
     * @return void
     */
    protected function getUsersBy($openids)
    {
        $chunks = array_chunk($openids, $this->getChunkSize($openids));

        $total = count($chunks);

        $bar = new ProgressBar($this->output, $total);

        $bar->setHeader('[开始同步]');

        $bar->start();
        foreach ($chunks as $chunk) {
            $users = $this->officialAccount->user->select($chunk);
            $this->syncToDatabase($users);
            $bar->advance();
        }
        $bar->finished();
    }


    /**
     * 同步到数据库
     *
     * @time 2020年06月20日
     * @param $users
     * @return void
     */
    protected function syncToDatabase($users)
    {
        $users = $users['user_info_list'];

        foreach ($users as &$user) {
            $user['avatar'] = $user['headimgurl'];
            $user['unionid'] = $user['unionid'] ?? '';
            $user['created_at'] = time();
            $user['updated_at'] = time();
            if (!empty($user['tagid_list'])) {
                $user['tagid_list'] = trim(implode(',', $user['tagid_list']), ',');
            }
            unset($user['headimgurl']);
            unset($user['qr_scene'], $user['qr_scene_str']);
        }

        WechatUsers::insertAll($users);
    }

    /**
     *  获取 chunk size
     *
     * @time 2020年06月20日
     * @param $openids
     * @return int
     */
    protected function getChunkSize($openids)
    {
        $size = count($openids);

        if ($size < 10) {
            return 1;
        }

        if ($size > 10 && $size < 100) {
            return 10;
        }

        if ($size > 100 && $size < 1000) {
            return 100;
        }

        if ($size > 1000 && $size < 10000) {
            return 100;
        }
    }

    /**
     * 获取微信 openids
     *
     * @time 2020年06月20日
     * @param $nextOpenId
     * @return mixed
     */
    public function getWechatUserOpenids($nextOpenId)
    {
        return $this->officialAccount->user->list($nextOpenId);
    }
}