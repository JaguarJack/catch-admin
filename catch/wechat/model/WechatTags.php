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

use catchAdmin\wechat\model\search\TagSearchTrait;
use catcher\base\CatchModel as Model;

class WechatTags extends Model
{
    use TagSearchTrait;

    protected $pk = 'tag_id';

    protected $name = 'wechat_tags';

    protected $field = [
        'id', // 
		'tag_id', // 微信 tagId
		'name', // 标签名称
        'fans_amount', // 粉丝数量
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];

    public function hasUsers()
    {
        return $this->belongsToMany(WechatUsers::class, 'wechat_user_has_tags', 'user_id', 'tag_id');
    }
}