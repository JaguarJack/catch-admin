<?php
namespace app;

// 应用请求对象类

use catchAdmin\user\Auth;

class Request extends \think\Request
{
    public function user()
    {
        return Auth::user();
    }
}
