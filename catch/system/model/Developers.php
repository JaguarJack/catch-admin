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

namespace catchAdmin\system\model;

use catchAdmin\system\model\search\DeveloperSearch;
use catcher\base\CatchModel as Model;

class Developers extends Model
{
    use DeveloperSearch;

    protected $name = 'developers';

    protected $field = [
        'id', // 
		'username', // 用户名
		'password', // 密码
		'mobile', // 手机号
		'id_card', // 身份证
		'alipay_account', // 支付宝账户
		'status', // 1 待认证 1 已认证
		'created_at', // 创建时间
		'updated_at', // 更新时间
		'deleted_at', // 软删除
    ];


    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
  
}