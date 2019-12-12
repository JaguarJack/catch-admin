<?php
namespace catchAdmin\login\controller;

use catchAdmin\login\Auth;
use catchAdmin\login\request\LoginRequest;
use catcher\base\CatchController;
use catcher\CatchResponse;

class Index extends CatchController
{
    /**
     * 登录
     *
     * @time 2019年11月30日
     * @throws \Exception
     * @return string
     */
    public function index(): string
    {
        return $this->fetch();
    }

    /**
     * 登陆
     *
     * @time 2019年11月28日
     * @param LoginRequest $request
     * @return bool|string
     * @throws \cather\exceptions\LoginFailedException
     * @throws \app\exceptions\LoginFailedException
     */
    public function login(LoginRequest $request)
    {
        return (new Auth())->login($request->param()) ?
            CatchResponse::success('', '登录成功') : CatchResponse::success('', '登录失败');
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