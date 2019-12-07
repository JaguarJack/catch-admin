<?php
namespace jaguarjack\think\module;

use jaguarjack\think\module\command\CreateModuleCommand;
use jaguarjack\think\module\command\DiscoverModuleServiceCommand;
use think\Service;

class ThinkModuleService extends Service
{
    public function boot()
    {
        $this->commands([
            CreateModuleCommand::class,
            DiscoverModuleServiceCommand::class,
        ]);
    }
}
