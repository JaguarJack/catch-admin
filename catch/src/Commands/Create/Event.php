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

declare(strict_types=1);

namespace Catch\Commands\Create;

use Catch\CatchAdmin;
use Catch\Commands\CatchCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

;

class Event extends CatchCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'catch:make:event {module} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create catch module event';


    public function handle()
    {
        $eventPath = CatchAdmin::getModuleEventPath($this->argument('module'));

        $file = $eventPath.$this->getEventFile();

        if (File::exists($file)) {
            $answer = $this->ask($file.' already exists, Did you want replace it?', 'Y');

            if (! Str::of($answer)->lower()->exactly('y')) {
                exit;
            }
        }

        File::put($file, Str::of($this->getStubContent())->replace([
            '{namespace}', '{event}'
        ], [trim(CatchAdmin::getModuleEventsNamespace($this->argument('module')), '\\'), $this->getEventName()])->toString());

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
    protected function getEventFile(): string
    {
        return $this->getEventName().'.php';
    }

    /**
     *
     *
     * @return string
     */
    protected function getEventName(): string
    {
        return  Str::of($this->argument('name'))
            ->whenContains('Event', function ($str) {
                return $str;
            }, function ($str) {
                return $str->append('Event');
            })->ucfirst()->toString();
    }

    /**
     * get stub content
     *
     * @return string
     */
    protected function getStubContent(): string
    {
        return File::get(dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'event.stub');
    }
}
