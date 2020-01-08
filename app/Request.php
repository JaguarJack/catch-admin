<?php
namespace app;

// 应用请求对象类

use catcher\CatchAuth;

class Request extends \think\Request
{
    protected $auth;

    public function user()
    {
        if (!$this->auth) {
          $this->auth = new CatchAuth;
        }

        return $this->auth->user();
    }
}
