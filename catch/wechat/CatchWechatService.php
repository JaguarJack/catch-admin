<?php
namespace catchAdmin\wechat;

use catchAdmin\wechat\command\SyncUsersCommand;
use catcher\ModuleService;
use think\Service;

class CatchWechatService extends ModuleService
{
    /**
     * register
     *
     * @time 2020年06月24日
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * register command
     *
     * @time 2020年06月24日
     * @return array
     */
    public function loadCommands()
    {
       return [__NAMESPACE__, __DIR__ . DIRECTORY_SEPARATOR . 'command'];
    }

    /**
     * loaded router from
     *
     * @time 2020年06月24日
     * @return string
     */
    public function loadRouteFrom()
    {
        // TODO: Implement loadRouteFrom() method.
        return __DIR__ . DIRECTORY_SEPARATOR . 'route.php';
    }
}