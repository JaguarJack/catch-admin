<?php

$router->get('/', '\catchAdmin\index\controller\index@index');
$router->get('theme', '\catchAdmin\index\controller\index@theme');
$router->get('dashboard', '\catchAdmin\index\controller\index@dashboard');
