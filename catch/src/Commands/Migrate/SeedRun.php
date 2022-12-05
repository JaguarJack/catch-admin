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
use Illuminate\Support\Facades\File;

class SeedRun extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:db:seed {module} {--class=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'catch db seed';

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
        $classes = $this->loadModuleSeeders();

        if ($class = $this->option('class')) {
            (new $class())->run();
        } else {
            foreach ($classes as $class) {
                $class = new $class();
                if (method_exists($class, 'run')) {
                    $class->run();
                }
            }
        }

        $this->info('Seed run successfully');
    }


    /**
     *
     * @time 2021年07月31日
     * @return array
     */
    protected function loadModuleSeeders(): array
    {
        $files = File::allFiles(CatchAdmin::getModuleSeederPath($this->argument('module')));

        $fileNames = [];

        foreach ($files as $file) {
            require_once $file->getRealPath();

            $fileNames[] = pathinfo($file->getBasename(), PATHINFO_FILENAME);
        }

        return $fileNames;
    }
}
