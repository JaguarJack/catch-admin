<?php
namespace app;

// 应用请求对象类

use catcher\CatchAuth;
use catcher\Code;
use catcher\exceptions\FailedException;
use thans\jwt\exception\TokenBlacklistException;
use thans\jwt\exception\TokenExpiredException;
use thans\jwt\exception\TokenInvalidException;

class Request extends \think\Request
{
    protected $auth;

  /**
   * login user
   *
   * @time 2020年01月09日
   * @return mixed
   */
  public function user()
  {
    if (!$this->auth) {
      $this->auth = new CatchAuth;
    }

    try {
        $user = $this->auth->user();
    }  catch (\Exception $e) {
        if ($e instanceof TokenExpiredException) {
            throw new FailedException('token 过期', Code::LOGIN_EXPIRED);
        }
        if ($e instanceof TokenBlacklistException) {
            throw new FailedException('token 被加入黑名单', Code::LOGIN_BLACKLIST);
        }
        if ($e instanceof TokenInvalidException) {
            throw new FailedException('token 不合法', Code::LOST_LOGIN);
        }
        throw new FailedException('auth failed', Code::LOST_LOGIN);
    }

    return $user;
  }
}
