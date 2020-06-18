<?php
namespace catcher;

use catcher\command\publish\WechatCommand;
use catcher\command\TestCommand;
use catcher\command\Tools\BackupCommand;
use catcher\command\Tools\CacheTrieCommand;
use catcher\command\Tools\CompressPackageCommand;
use catcher\command\CreateModuleCommand;
use catcher\command\install\InstallProjectCommand;
use catcher\command\MigrateCreateCommand;
use catcher\command\MigrateRollbackCommand;
use catcher\command\MigrateRunCommand;
use catcher\command\ModelGeneratorCommand;
use catcher\command\ModuleCacheCommand;
use catcher\command\SeedRunCommand;
use catcher\command\Tools\ExportDataCommand;
use catcher\command\Tools\MakeMenuCommand;
use catcher\command\worker\ExcelTaskCommand;
use catcher\command\worker\WsWorkerCommand;
use think\exception\Handle;
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
    }

    /**
     * register
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/30
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerValidates();
        $this->registerMiddleWares();
        $this->registerEvents();
        $this->registerQuery();
        $this->registerExceptionHandle();
    }

    /**
     *
     * @time 2019年12月13日
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            InstallProjectCommand::class,
            ModuleCacheCommand::class,
            MigrateRunCommand::class,
            ModelGeneratorCommand::class,
            SeedRunCommand::class,
            BackupCommand::class,
            CompressPackageCommand::class,
            CreateModuleCommand::class,
            MigrateRollbackCommand::class,
            MigrateCreateCommand::class,
            WsWorkerCommand::class,
            ExportDataCommand::class,
            MakeMenuCommand::class,
            ExcelTaskCommand::class,
            WechatCommand::class,
            CacheTrieCommand::class,
        ]);
    }
    /**
     *
     * @time 2019年12月07日
     * @return void
     */
    protected function registerValidates(): void
    {
        $validates = config('catch.validates');

        Validate::maker(function($validate) use ($validates) {
            foreach ($validates as $vali) {
                $vali = app()->make($vali);
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
      // todo
    }

    /**
     * 注册监听者
     *
     * @time 2019年12月12日
     * @return void
     */
    protected function registerEvents(): void
    {
        $this->app->event->listenEvents(config('catch.events'));
    }

  /**
   * register query
   *
   * @time 2020年02月20日
   * @return void
   */
    protected function registerQuery(): void
    {
        $connections = $this->app->config->get('database.connections');

        $connections['mysql']['query'] = CatchQuery::class;

        $this->app->config->set([
          'connections' => $connections
        ], 'database');
    }

  /**
   * register exception
   *
   * @time 2020年02月20日
   * @return void
   */
    protected function registerExceptionHandle(): void
    {
        $this->app->bind(Handle::class, CatchExceptionHandle::class);
    }
}
