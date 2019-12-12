<?php
namespace catchAdmin\login;

use catchAdmin\user\model\Users;
use cather\exceptions\LoginFailedException;
use think\facade\Session;

class Auth
{
    protected $loginUser = 'admin_user';

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        $this->loginUser = md5($this->loginUser);
    }

    /**
     * 登陆
     *
     * @time 2019年11月28日
     * @param $params
     * @throws LoginFailedException
     * @return bool
     */
    public function login($params)
    {
        $user = Users::where('username', $params['name'])->find();
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

        Session::set($this->loginUser, $user);

        return true;
    }

    /**
     * 退出登陆
     *
     * @time 2019年11月28日
     * @return bool
     */
    public function logout()
    {
        Session::delete($this->loginUser);

        return true;
    }
}
