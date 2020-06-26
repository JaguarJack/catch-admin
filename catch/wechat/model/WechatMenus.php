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

use think\Model;
use catcher\traits\db\BaseOptionsTrait;

class WechatMenus extends Model
{
    use BaseOptionsTrait;

    protected $name = 'wechat_menus';

    protected $field = [
        'id', // 
		'name', // 菜单名称
		'parent_id', // 父级ID
		'type', // 类型
		'key', // key
		'url', // view 类型  url 链接
		'appid', // 小程序 appid
		'pagepath', // 小程序页面
		'media_id', // 调用新增永久素材接口返回的合法media_id
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];
    
  
}