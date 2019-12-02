<?php
namespace catchAdmin\login;

use app\exceptions\LoginFailedException;
use think\Db;
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
        $user = Db::table('admin_users')
                    ->where('name', $params['name'])
                    // ->where('password', $params['password'])
                    ->first();

        if (!password_verify($params('password'), $user->password)) {
            throw new LoginFailedException('登陆失败, 请检查用户名和密码');
        }

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
