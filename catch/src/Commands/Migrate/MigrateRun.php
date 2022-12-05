<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace Catch\Commands\Migrate;

use Catch\CatchAdmin;
use Catch\Commands\CatchCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrateRun extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:migrate {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate catch module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $module = $this->argument('module');

        if (File::isDirectory(CatchAdmin::getModuleMigrationPath($module))) {
            foreach (File::files(CatchAdmin::getModuleMigrationPath($module)) as $file) {
                $path = Str::of(CatchAdmin::getModuleRelativePath(CatchAdmin::getModuleMigrationPath($module)))

                    ->remove('.')->append($file->getFilename());

                Artisan::call('migrate', [
                    '--path' => $path,

                    '--force' => $this->option('force')
                ]);
            }

            $this->info("Module [$module] migrate success");
        } else {
            $this->error('No migration files in module');
        }
    }
}
