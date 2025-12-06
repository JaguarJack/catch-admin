<?php

namespace Modules\Common\Http\Controllers;

use Exception;


/**
 * @group 公共模块
 *
 * @subgroup 功能开启
 * @subgroupDescription CatchAdmin 是否开启
 */
class SettingController
{
    /**
     * 登录微信/手机功能是否开启
     *
     * @responseField wechat bool 微信登录功能是否开启
     * @responseField mobile bool 手机登录功能是否开启
     *
     * @throws Exception
     */
    public function config(): array
    {
        return \config('setting', []);
    }
}
