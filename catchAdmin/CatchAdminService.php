<?php
namespace catchAdmin;

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
}