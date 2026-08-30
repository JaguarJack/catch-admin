<?php

namespace Modules\System\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Illuminate\Http\Request;
use Modules\System\Models\SystemConfig as Config;
use Modules\System\Support\Configure;

class SmsConfigController extends Controller
{
    public function store(Request $request, Config $configModel)
    {
        if ($default = $request->get('default')) {
            return $configModel->storeBy([
                'sms.default' => $default,
            ]);
        }

        $driver = $request->get('channel');

        return $configModel->storeBy(Configure::parse("sms.$driver", $request->except('channel')));
    }

    public function show($driver)
    {
        return \config('sms.' . $driver);
    }
}
