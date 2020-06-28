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

use catcher\base\CatchModel as Model;
use catcher\traits\db\BaseOptionsTrait;

class WechatGraphic extends Model
{
    protected $name = 'wechat_graphic';

    protected $field = [
        'id', // 
		'title', // 标题
		'author', // 作者
		'parent_id', // 图文第一篇
		'cover', // 封面
		'content', // 内容
		'creator_id', // 创建人ID
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];
    
  
}