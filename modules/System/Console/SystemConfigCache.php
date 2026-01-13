<?php

namespace Modules\System\Console;

use Illuminate\Console\Command;
use Modules\System\Support\Configure;

class SystemConfigCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:config:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '缓存系统配置相关';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        (new Configure)->cache();

        $this->info('🎉 缓存系统配置成功');
    }
}
