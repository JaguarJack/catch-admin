<?php

namespace Modules\Permissions\Providers;

use Catch\Support\Module\Installer as ModuleInstaller;

class Installer extends ModuleInstaller
{
    protected function info(): array
    {
        // TODO: Implement info() method.
        return [
            'name' => '权限管理',
            'path' => 'Permissions',
            'keywords' => '权限, 角色, 部门',
            'description' => '权限管理模块',
            'provider' => PermissionsServiceProvider::class
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
