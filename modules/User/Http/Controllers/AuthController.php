<?php

namespace Modules\User\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\User\Events\Login;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function login(Request $request)
    {
        $token = Auth::guard(getGuardName())->attempt($request->only(['email', 'password']));

        Event::dispatch(new Login($request, $token));

        if (! $token) {
            throw new FailedException('登录失败！请检查邮箱或者密码');
        }

        return compact('token');
    }


    /**
     * logout
     *
     * @return bool
     */
    public function logout()
    {
        //  Auth::guard(Helper::getGuardName())->logout();

        return true;
    }
}
