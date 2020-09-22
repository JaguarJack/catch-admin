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
        try {
            return CatchResponse::success([
                'token' => $auth->attempt($request->param()),
            ], '登录成功');
        } catch (\Exception $exception) {
           $code = $exception->getCode();
           return CatchResponse::fail($code == Code::USER_FORBIDDEN ?
               '该账户已被禁用，请联系管理员' : '登录失败,请检查邮箱和密码', Code::LOGIN_FAILED);
        }
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
