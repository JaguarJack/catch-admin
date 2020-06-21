<?php
/**
 * @filename  GetModuleTrait.php
 * @createdAt 2020/2/24
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */

namespace catchAdmin\wechat\command;

use catchAdmin\wechat\model\WechatUsers;
use catcher\facade\Trie;
use catcher\library\ProgressBar;
use catcher\library\WeChat;
use think\Collection;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class SyncUsersCommand extends Command
{
    protected $officialAccount;

    public function configure()
    {
        $this->setName('sync:users')
            ->setDescription('sync wechat users');
    }

    public function execute(Input $input, Output $output)
    {
        $this->officialAccount = WeChat::officialAccount();

        $this->sync(null);
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
