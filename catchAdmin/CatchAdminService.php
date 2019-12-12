<?php
namespace catchAdmin;

use catchAdmin\login\LoginLogListener;
use catchAdmin\permissions\OperateLogListener;
use catchAdmin\permissions\PermissionsMiddleware;
use catchAdmin\system\event\LoginLogEvent;
use catchAdmin\system\event\OperateLogEvent;
use catcher\command\InstallCommand;
use catcher\command\MigrateRunCommand;
use catcher\command\ModelGeneratorCommand;
use catcher\command\ModuleCacheCommand;
use catcher\command\SeedRunCommand;
use catcher\validates\Sometimes;
use think\facade\Validate;
use think\Service;

class CatchAdminService extends Service
{
    /**
     *
     * @time 2019年11月29日
     * @return void
     */
    public function boot()
    {
        $this->commands([
            InstallCommand::class,
            ModuleCacheCommand::class,
            MigrateRunCommand::class,
            ModelGeneratorCommand::class,
            SeedRunCommand::class
        ]);

        $this->registerValidates();
        $this->registerMiddleWares();
        $this->registerEvents();
        $this->registerListeners();
    }

    /**
     *
     * @time 2019年12月07日
     * @return void
     */
    protected function registerValidates(): void
    {
        $validates = [
            new Sometimes(),
        ];

        Validate::maker(function($validate) use ($validates){
            foreach ($validates as $vali) {
                $validate->extend($vali->type(), [$vali, 'verify'], $vali->message());
            }
        });
    }

    /**
     *
     * @time 2019年12月12日
     * @return void
     */
    protected function registerMiddleWares(): void
    {
        $this->app->middleware->import([
            'check_auth' => PermissionsMiddleware::class
        ], 'route');
    }

    /**
     *
     * @time 2019年12月12日
     * @return void
     */
    protected function registerEvents(): void
    {
        $this->app->event->bind([
            'loginLog' => LoginLogEvent::class,
            'operateLog' => OperateLogEvent::class,
        ]);


    }

    /**
     * 注册监听者
     *
     * @time 2019年12月12日
     * @return void
     */
    protected function registerListeners(): void
    {
        $this->app->event->listenEvents([
            'loginLog' => [
                LoginLogListener::class,
            ],
            'operateLog' => [
                OperateLogListener::class,
            ],
        ]);
    }

}