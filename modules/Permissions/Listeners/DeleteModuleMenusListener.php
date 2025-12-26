<?php

namespace Modules\Permissions\Listeners;

use Illuminate\Support\Facades\DB;
use Modules\Permissions\Events\DeleteModuleMenusEvent;
use Modules\Permissions\Models\Permissions;

/**
 * 删除模块菜单
 */
class DeleteModuleMenusListener
{
    public function handle(DeleteModuleMenusEvent $event): void
    {
        DB::transaction(function () use ($event) {
            $permissionIds = Permissions::where('module', $event->moduleName)->pluck('id');

            Permissions::whereIn('parent_id', $permissionIds)->delete();

            DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        });
    }
}
