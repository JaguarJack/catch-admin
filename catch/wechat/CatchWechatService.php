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

        $this->registerCommand();
    }

    /**
     * register command
     *
     * @time 2020年06月24日
     * @return void
     */
    public function registerCommand()
    {
        $this->commands([
            SyncUsersCommand::class,
        ]);
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