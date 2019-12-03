<?php
namespace catchAdmin;

use catcher\command\InstallCommand;
use catcher\command\MigrateRunCommand;
use catcher\command\ModelGeneratorCommand;
use catcher\command\ModuleCacheCommand;
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
        ]);
    }
}