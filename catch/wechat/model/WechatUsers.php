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

namespace catchAdmin\wechat\model;

use catchAdmin\wechat\model\search\UserSearchTrait;
use catcher\base\CatchModel;
use think\facade\Db;

class WechatUsers extends CatchModel
{
    use UserSearchTrait;

    protected $name = 'wechat_users';

    protected $field = [
        'id', // 
	    'nickname', // 用户名',
	    'avatar', // 用户头像',
        'openid', // openid',
        'language', // 语言',
        'country', // 国家',
        'province', // 省份',
        'city', // 城市',
        'subscribe', // 用户状态  0 取消订阅  1 订阅',
        'block', // 拉黑状态  1 正常  2 拉黑',
        'subscribe_time', // 订阅时间',
        'subscribe_scene', // 订阅场景 ADD_SCENE_SEARCH 公众号搜索，ADD_SCENE_ACCOUNT_MIGRATION 公众号迁移，ADD_SCENE_PROFILE_CARD 名片分享，ADD_SCENE_QR_CODE 扫描二维码，ADD_SCENE_PROFILE_LINK 图文页内名称点击，ADD_SCENE_PROFILE_ITEM 图文页右上角菜单，ADD_SCENE_PAID 支付后关注，ADD_SCENE_WECHAT_ADVERTISEMENT 微信广告，ADD_SCENE_OTHERS 其他',
        'unionid', // 用户平台唯一身份认证',
        'sex', // 用户状态 1 男 2 女 0 未知',
        'remark', // 备注',
        'groupid', // 分组ID',
        'tagid_list', // 标签列表',
        'created_at', // 创建时间',
        'updated_at', // 更新时间',
        'deleted_at', // 删除状态，0未删除 >0 已删除',
    ];

    const BlOCK = 2; // 拉黑
    const UNBLOCK = 1; // 取消拉黑

    public function hasTags()
    {
        return $this->belongsToMany(WechatTags::class, 'wechat_user_has_tags', 'tag_id', 'user_id');
    }

    public function scopeTags($query)
    {
        return $query->addSelectSub(function () {
            return Db::name('wechat_user_has_tags')
                        ->whereColumn('wechat_user_has_tags.user_id', $this->aliasField('id'))
                        ->leftJoin('wechat_tags','wechat_user_has_tags.tag_id=wechat_tags.tag_id')
                        ->field(Db::raw('group_concat(`wechat_tags`.name)'));
        }, 'tags');
    }

}