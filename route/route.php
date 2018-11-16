<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
# 登录
Route::rule('login','admin/login/login','GET|POST')->name('login');
# 登出
Route::rule('logout','admin/login/logout')->name('logout');
# 后台首页
Route::get('index','admin/index/index')->name('index');