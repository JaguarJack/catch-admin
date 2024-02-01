<?php

namespace Modules\User\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Modules\User\Events\Login;
use Modules\User\Models\User;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function login(Request $request): array
    {
        /* @var User $user */
        $user = User::query()->where('email', $request->get('email'))->first();

        Event::dispatch(new Login($request, $user ? ($user->isDisabled() ? null : $user) : null));

        if ($user) {
            if ($user->isDisabled()) {
                throw new FailedException('账号被禁用，请联系管理员');
            }

            if (Hash::check($request->get('password'), $user->password)) {
                $token = $user->createToken('token')->plainTextToken;
                return compact('token');
            }
        }

        throw new FailedException('登录失败！请检查邮箱或者密码');
    }


    /**
     * logout
     *
     * @return array
     */
    public function logout(): array
    {
        /* @var  User $user */
        $user = Auth::guard(getGuardName())->user();

        $user->currentAccessToken()->delete();

        return [];
    }
}
