<?php

# 登陆页面
$router->get('login', '\catchAdmin\login\controller\Index/index');
# 登入
$router->post('login', '\catchAdmin\login\controller\Index/login');
# 登出
$router->post('logout', '\catchAdmin\login\controller\Index/logout');
