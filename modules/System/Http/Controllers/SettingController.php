<?php

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Modules\System\Models\SystemConfig as Config;
use Modules\System\Support\Configure;

/**
 * @group 系统设置
 *
 * @subgroup 系统设置
 * @subgroupDescription CatchAdmin 系统设置
 */
class SettingController extends Controller
{
    /**
     * 保存设置
     *
     * @param  Request  $request
     * @param  Config  $configModel
     * @return mixed
     */
    public function store(Request $request, Config $configModel)
    {
        return $configModel->storeBy(Configure::parse('setting', $request->all()));
    }

    /**
     * 获取设置
     *
     * @return Repository|Application|mixed|object|null
     */
    public function show()
    {
        return \config('setting', []);
    }
}
