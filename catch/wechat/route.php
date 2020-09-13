<?php
/**
 * @filename route.php
 * @date     2020/6/7
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */

$router->group('wechat', function () use ($router){
    // 公众号粉丝
    $router->group('official/users', function () use ($router){
        $router->get('', '\catchAdmin\wechat\controller\Users@index');
        $router->put('remark/<id>/<remark>', '\catchAdmin\wechat\controller\Users@remark');
        $router->put('block/<id>', '\catchAdmin\wechat\controller\Users@block');
        $router->put('tag/<id>', '\catchAdmin\wechat\controller\Users@tag');
        $router->get('sync', '\catchAdmin\wechat\controller\Users@sync');
    });
    // 粉丝标签
    $router->group('official/tags', function () use ($router){
        $router->resource('', '\catchAdmin\wechat\controller\Tags');
        $router->get('sync', '\catchAdmin\wechat\controller\Tags@sync');
    });
    // 微信菜单
    $router->group('official/menus', function () use ($router){
        $router->resource('', '\catchAdmin\wechat\controller\Menus');
        $router->post('sync', '\catchAdmin\wechat\controller\Menus@sync');
    });
    // 图文管理
    $router->group('official/graphic', function () use ($router){
        $router->resource('', '\catchAdmin\wechat\controller\Graphic');
    });
    // 微信回复管理
    $router->group('official/reply', function () use ($router){
        $router->resource('', '\catchAdmin\wechat\controller\Reply');
        $router->put('enable/<id>', '\catchAdmin\wechat\controller\Reply@disOrEnable');
    });
    // 微信上传
    $router->group('official/upload', function () use ($router){
        $router->post('/image', '\catchAdmin\wechat\controller\Upload@image');
        $router->post('/file', '\catchAdmin\wechat\controller\Upload@file');
    });
})->middleware('auth');

// 消息
$router->rule('wechat', '\catchAdmin\wechat\controller\Message@done', 'GET|POST');