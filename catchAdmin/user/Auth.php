<?php
namespace catchAdmin\user;

use catchAdmin\user\model\Users;
use catcher\exceptions\FailedException;
use catcher\exceptions\LoginFailedException;
use think\facade\Session;

class Auth
{
    protected const USER_KEY = 'admin_user';

    /**
     * 登陆
     *
     * @time 2019年11月28日
     * @param $params
     * @throws LoginFailedException
     * @return bool
     */
    public static function login($params)
    {
        $user = Users::where('email', $params['email'])->find();

        if (!$user) {
            throw new LoginFailedException('登陆失败, 请检查用户名和密码');
        }

        if (!password_verify($params['password'], $user->password)) {
            throw new LoginFailedException('登陆失败, 请检查用户名和密码');
        }

        if ($user->status == Users::DISABLE) {
            throw new LoginFailedException('该用户已被禁用');
        }

        // 记录用户登录
        $user->last_login_ip = ip2long(request()->ip());
        $user->last_login_time = time();
        $user->save();

        Session::set(self::getLoginUserKey(), $user);

        return true;
    }

    /**
     * 退出登陆
     *
     * @time 2019年11月28日
     * @return bool
     */
    public static function logout(): bool
    {
        Session::delete(self::getLoginUserKey());

        return true;
    }

    public static function user()
    {
        return Session::get(self::getLoginUserKey(), null);
    }

    protected static function getLoginUserKey(): string
    {
        return md5(self::USER_KEY);
    }
}
