<?php
namespace catchAdmin\login\controller;

use app\BaseController;
use catchAdmin\login\Auth;
use catchAdmin\login\request\LoginRequest;

class Index extends BaseController
{
    /**
     * 登录
     *
     * @time 2019年11月30日
     * @throws \Exception
     * @return string
     */
    public function index()
    {
        return $this->fetch('login::index');
    }

    /**
     * 登陆
     *
     * @time 2019年11月28日
     * @param LoginRequest $request
     * @return bool|string
     * @throws \app\exceptions\LoginFailedException
     */
    public function login(LoginRequest $request)
    {
        (new Auth())->login($request->post());
    }

    /**
     * 登出
     *
     * @time 2019年11月28日
     * @return bool
     */
    public function logout(): bool
    {
        if ((new Auth())->logout()) {
            return redirect(url('login'));
        }

        return false;
    }
}