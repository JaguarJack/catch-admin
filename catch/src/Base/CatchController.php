<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace Catch\Base;

use Catch\Enums\Code;
use Catch\Exceptions\FailedException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * base catch controller
 */
abstract class CatchController extends Controller
{
    /**
     * @param $guard
     * @return Authenticatable
     */
    protected function getLoginUser($guard = null): Authenticatable
    {
        $user = Auth::guard($guard ?: getGuardName())->user();

        if (! $user) {
            throw new FailedException('登录失效, 请重新登录', Code::LOST_LOGIN);
        }

        return $user;
    }
}
