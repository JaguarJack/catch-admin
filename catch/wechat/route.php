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
        $router->get('<nextOpenid?>', '\catchAdmin\wechat\controller\Users@index');
        $router->put('remark/<id>/<remark>', '\catchAdmin\wechat\controller\Users@remark');
        $router->put('block/<id>', '\catchAdmin\wechat\controller\Users@block');
    });
});
