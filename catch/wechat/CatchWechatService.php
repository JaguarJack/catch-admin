<?php
namespace catchAdmin\wechat;

use catchAdmin\wechat\command\SyncUsersCommand;
use think\Service;

class CatchWechatService extends Service
{
    public function boot()
    {}

    public function register()
    {
        $this->registerCommand();
    }

    public function registerCommand()
    {
        $this->commands([
            SyncUsersCommand::class,
        ]);
    }
}