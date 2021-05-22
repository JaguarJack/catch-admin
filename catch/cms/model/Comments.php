<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\model;

class Comments extends BaseModel
{
    // 表名
    public $name = 'cms_comments';
    // 数据库字段映射
    public $field = array(
        'id',
        // 文章ID
        'article_id',
        // 内容
        'content',
        // 父ID
        'parent_id',
        // 评论者ID
        'user_id',
        // ip 地址
        'ip',
        // agent
        'user_agent',
        // 1 展示 2 隐藏
        'status',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );
}