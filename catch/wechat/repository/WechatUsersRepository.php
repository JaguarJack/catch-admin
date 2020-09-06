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

use catchAdmin\wechat\model\WechatTags;
use catchAdmin\wechat\model\WechatUsers;
use catcher\base\CatchRepository;
use catcher\library\WeChat;
use catcher\Utils;

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
     * 获取列表
     *
     * @time 2020年06月21日
     * @return mixed
     */
    public function getList()
    {
        return $this->wechatUser
                    ->catchSearch()
                    ->field('*')
                    ->tags()
                    ->catchOrder()
                    ->paginate();
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

    /**
     * 给用户打标签
     *
     * @time 2020年06月21日
     * @param $id
     * @param $data
     * @return mixed
     */
    public function tag($id, $data)
    {
        $tagIds = Utils::stringToArrayBy($data['tag']);
            // WechatTags::whereIn('name', Utils::stringToArrayBy($data['tag']))->column('tag_id');

        $user = $this->findBy($id);

        $hasTagIds = $user->hasTags()->select()->column('tag_id');

        // 已存在的标签
        $existedTagIds = [];
        foreach ($tagIds as $tagId) {
            if (in_array($tagId, $hasTagIds)) {
                $existedTagIds[] = $tagId;
            }
        }
        $detachIds = array_diff($hasTagIds, $existedTagIds);
        $attachIds = array_diff($tagIds, $existedTagIds);

        $officialUserTag = WeChat::officialAccount()->user_tag;
        // 删除标签
        if (!empty($detachIds)) {
            foreach ($detachIds as $detachId) {
                $officialUserTag->untagUsers([$user->openid], $detachId);
            }
            $user->hasTags()->detach($detachIds);
        }

        // 新增标签
        if (!empty($attachIds)) {
            foreach ($attachIds as $attachId) {
                $officialUserTag->tagUsers([$user->openid], $attachId);
            }
            $user->hasTags()->saveAll($attachIds);
        }

        WechatUsers::where('id', $id)->update([
            'tagid_list' => $data['tag'],
        ]);

        return true;
    }
}