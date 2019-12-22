<?php

$router->get('/', '\catchAdmin\index\controller\Index@index');
$router->get('theme', '\catchAdmin\index\controller\Index@theme');
$router->get('dashboard', '\catchAdmin\index\controller\Index@dashboard');
