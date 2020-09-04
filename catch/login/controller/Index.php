<?php
namespace catchAdmin\login\controller;

use catchAdmin\login\request\LoginRequest;
use catchAdmin\permissions\model\Users;
use catcher\base\CatchController;
use catcher\CatchAuth;
use catcher\CatchResponse;
use catcher\Code;
use catcher\exceptions\LoginFailedException;
use thans\jwt\facade\JWTAuth;

class Index extends CatchController
{
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
          throw new LoginFailedException('该用户已被禁用', Code::USER_FORBIDDEN);
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
   */
    public function logout(): \think\response\Json
    {
        return CatchResponse::success();
    }

    /**
     * refresh token
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/5/18
     * @return \think\response\Json
     */
    public function refreshToken()
    {
        return CatchResponse::success([
            'token' => JWTAuth::refresh()
        ]);
    }
}
