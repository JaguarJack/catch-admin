<?php

namespace Modules\Permissions;

use Catch\Support\Module\Installer as ModuleInstaller;
use Modules\Permissions\Providers\PermissionsServiceProvider;

class Installer extends ModuleInstaller
{
    protected function info(): array
    {
        // TODO: Implement info() method.
        return [
            'title' => '权限管理',
            'name' => 'permissions',
            'path' => 'permissions',
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
