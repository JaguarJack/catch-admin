<?php
namespace Modules\System\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\System\Support\Configure;

/**
 * 动态配置监听者
 */
class DynamicConfigureListener
{
    public function handle($event): void
    {
        $config = $event->sandbox->make('config');

        $configure = new Configure();
        $configure->loadToLaravelConfig($config, fn($key) => Cache::delete($key));
    }
}
