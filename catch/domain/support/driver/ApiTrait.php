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

        $apiDomain = config('catch.domains.' . $name . '.api_domain');

        if (strpos($apiDomain, 'https') === false &&
        strpos($apiDomain, 'http') === false) {
            $apiDomain = 'https://' . $apiDomain . '/v2/index.php';
        }

        return Http::ignoreSsl()->query(CommonParams::{$name}($params))
            ->get($apiDomain)->json();
    }
}