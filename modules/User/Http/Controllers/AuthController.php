<?php

namespace Modules\User\Http\Controllers;

use Catch\Base\CatchController as Controller;
use Catch\Exceptions\FailedException;
use Illuminate\Auth\RequestGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
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

        $token = $user?->createToken('token')->plainTextToken;

        Event::dispatch(new Login($request, $user));

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
    public function logout(): bool
    {
        /* @var  User $user */
        $user = Auth::guard(getGuardName())->user();

        return $user->currentAccessToken()->delete();
    }
}
