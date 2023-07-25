<?php

namespace Modules\System;

use Catch\Support\Module\Installer as ModuleInstaller;
use Modules\System\Providers\SystemServiceProvider;

class Installer extends ModuleInstaller
{
    protected function info(): array
    {
        // TODO: Implement info() method.
        return [
            'title' => '系统管理',
            'name' => 'system',
            'path' => 'system',
            'keywords' => '系统管理, system',
            'description' => '系统管理模块',
            'provider' => SystemServiceProvider::class
        ];
    }

    protected function requirePackages(): void
    {
        // TODO: Implement requirePackages() method.
    }

    protected function removePackages(): void
    {
        // TODO: Implement removePackages() method.
    }
}
