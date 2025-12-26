<?php

namespace Modules\Permissions\Listeners;

use Catch\Enums\Status;
use Modules\Permissions\Events\DisableModuleMenusEvent;
use Modules\Permissions\Models\Permissions;

class DisableModuleMenusListener
{
    public function handle(DisableModuleMenusEvent $event)
    {
        Permissions::where('module', $event->moduleName)
            ->when(is_array($event->permissionMark), function ($query) use ($event) {
                $query->whereNotIn('permission_mark', $event->permissionMark);
            })
            ->when(is_string($event->permissionMark), function ($query) use ($event) {
                $query->whereNot('permission_mark', $event->permissionMark);
            })
            ->update(['hidden' => Status::Disable->value]);
    }
}
