<?php

// you should user `$router`
$router->group(function () use ($router){

});



//hello路由
$router->resource('hello', '\catchAdmin\cms\controller\Hello')->middleware('auth');

//hello路由
$router->resource('hello', '\catchAdmin\cms\controller\Hello')->middleware('auth');