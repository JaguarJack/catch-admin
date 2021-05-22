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

class Users extends BaseModel
{
    // 表名
    public $name = 'cms_users';
    // 数据库字段映射
    public $field = array(
        'id',
        // 用户名
        'username',
        // 邮箱
        'email',
        // 手机号
        'mobile',
        // 头像
        'avatar',
        // 1 正常 2 禁用
        'status',
        // 密码
        'password',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );
}