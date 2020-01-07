<?php
namespace catchAdmin\login\controller;

use catchAdmin\user\Auth;
use catchAdmin\login\request\LoginRequest;
use catchAdmin\user\model\Users;
use catcher\base\CatchController;
use catcher\CatchAuth;
use catcher\CatchResponse;
use catcher\exceptions\LoginFailedException;
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
   * @param CatchAuth $auth
   * @return bool|string
   */
    public function login(LoginRequest $request, CatchAuth $auth)
    {
        $params = $request->param();
     
        $token = $auth->attempt($params);

        $user = $auth->user();

        if ($user->status == Users::DISABLE) {
          throw new LoginFailedException('该用户已被禁用');
        }

        // 记录用户登录
        $user->last_login_ip = request()->ip();
        $user->last_login_time = time();
        $user->save();

        // 登录事件
        $params['success'] = $token;

        event('loginLog', $params);

        return $token ? CatchResponse::success([
            'token' => $token,
        ], '登录成功') : CatchResponse::success('', '登录失败');
    }

    /**
     * 登出
     *
     * @time 2019年11月28日
     * @return \think\response\Json
     * @throws \Exception
     */
    public function logout(): \think\response\Json
    {
        if (Auth::logout()) {
            return CatchResponse::success();
        }

        return CatchResponse::fail('登出失败');
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
