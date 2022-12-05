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

namespace Catch\Support\Module;

use Catch\Support\Module\Driver\DatabaseDriver;
use Catch\Support\Module\Driver\FileDriver;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Manager;

class ModuleManager extends Manager
{
    public function __construct(Container|\Closure $container)
    {
        if ($container instanceof \Closure) {
            $container = $container();
        }

        parent::__construct($container);
    }

    /**
     * @return string
     */
    public function getDefaultDriver(): string
    {
        // TODO: Implement getDefaultDriver() method.
        return $this->config->get('catch.module.driver.default');
    }

    /**
     * create file driver
     *
     * @return FileDriver
     */
    public function createFileDriver(): FileDriver
    {
        return new FileDriver();
    }

    /**
     * create database driver
     *
     * @return DatabaseDriver
     */
    public function createDatabaseDriver(): DatabaseDriver
    {
        return new DatabaseDriver();
    }
}
