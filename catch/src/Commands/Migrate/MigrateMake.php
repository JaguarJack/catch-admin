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
use Illuminate\Support\Str;

class MigrateMake extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:migration {module : The module of the migration created at}
        {table : The name of the table to migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create module migration';


    /**
     *
     *
     * @return void
     */
    public function handle(): void
    {
        $migrationPath = CatchAdmin::getModuleMigrationPath($this->argument('module'));

        $file = $migrationPath.$this->getMigrationFile();

        File::put($file, Str::of($this->getStubContent())->replace(
            '{table}',
            $this->getTable()
        )->toString());


        if (File::exists($file)) {
            $this->info($file.' has been created');
        } else {
            $this->error($file.' create failed');
        }
    }

    /**
     *
     *
     * @return string
     */
    protected function getMigrationFile(): string
    {
        return date('Y_m_d_His').'_create_'.$this->getTable().'.php';
    }

    /**
     *
     *
     * @return string
     */
    protected function getTable(): string
    {
        return  Str::of($this->argument('table'))->ucfirst()->snake()->lower()->toString();
    }

    /**
     * get stub content
     *
     * @return string
     */
    protected function getStubContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'migration.stub');
    }
}
