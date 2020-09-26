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
namespace catchAdmin\domain\support\driver;

use catcher\facade\Http;
use catchAdmin\domain\support\CommonParams;

trait ApiTrait
{
    public function get(array $params)
    {
        $name = config('catch.domains.default');

        return Http::query(CommonParams::{$name}($params))
            ->get(config('catch.domains.' . $name . '.api_domain'))->json();
    }
}