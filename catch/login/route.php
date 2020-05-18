<?php
# 登入
$router->post('login', '\catchAdmin\login\controller\Index@login');
$router->post('logout', '\catchAdmin\login\controller\Index@logout');

