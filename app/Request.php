<?php
namespace app;

// 应用请求对象类

use catcher\CatchAuth;

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

    return $this->auth->user();
  }
}
