<?php
namespace catchAdmin\login\controller;

use catchAdmin\user\Auth;
use catchAdmin\login\request\LoginRequest;
use catcher\base\CatchController;
use catcher\CatchResponse;
use think\captcha\Captcha;

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
        return Auth::login($request->param()) ?
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
        if (Auth::logout()) {
            return redirect(url('login'));
        }

        return false;
    }

    /**
     *
     * @time 2019年12月12日
     * @param Captcha $captcha
     * @param null $config
     * @return \think\Response
     */
    public function captcha(Captcha $captcha, $config = null): \think\Response
    {
        return $captcha->create($config);
    }
}