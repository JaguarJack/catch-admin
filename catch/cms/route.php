<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

/* @var $router \think\Route */
$router->group('cms', function () use ($router){
	// users路由
	$router->resource('users', '\catchAdmin\cms\controller\Users');
	// category路由
	$router->resource('category', '\catchAdmin\cms\controller\Category');
    $router->post('import/category', '\catchAdmin\cms\controller\Category@import');

    // articles路由
	$router->resource('articles', '\catchAdmin\cms\controller\Articles');
	// tags路由
	$router->resource('tags', '\catchAdmin\cms\controller\Tags');
	// comments路由
	$router->resource('comments', '\catchAdmin\cms\controller\Comments');
	// banners路由
	$router->resource('banners', '\catchAdmin\cms\controller\Banners');
	// siteLInks路由
	$router->resource('site/links', '\catchAdmin\cms\controller\SiteLinks');
	// forms路由
	$router->resource('forms', '\catchAdmin\cms\controller\Forms');
	// formFields路由
	$router->resource('formFields', '\catchAdmin\cms\controller\FormFields');
	// formData路由
	$router->resource('formData', '\catchAdmin\cms\controller\FormData');
	// model路由
	$router->resource('model', '\catchAdmin\cms\controller\Models');
	// modelFields路由
	$router->resource('modelFields', '\catchAdmin\cms\controller\ModelFields');
	// form create
    $router->get('form/<name>/create', '\catchAdmin\cms\controller\Form@create');
    // 上传
    $router->group('upload', function () use ($router){
        $router->post('image', '\catchAdmin\cms\controller\Upload@image');
        $router->post('file', '\catchAdmin\cms\controller\Upload@file');
    })->middleware(\catcher\middlewares\JsonResponseMiddleware::class);
})->middleware('auth');