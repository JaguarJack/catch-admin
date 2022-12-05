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

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\VarDumper\VarDumper;

/**
 * load commands
 */
if (! function_exists('loadCommands')) {
    /**
     * @throws ReflectionException
     */
    function loadCommands($paths, $namespace, $searchPath = null): void
    {
        if (! $searchPath) {
            $searchPath = dirname($paths).DIRECTORY_SEPARATOR;
        }

        $paths = Collection::make(Arr::wrap($paths))->unique()->filter(function ($path) {
            return is_dir($path);
        });

        if ($paths->isEmpty()) {
            return;
        }

        foreach ((new Finder())->in($paths->toArray())->files() as $command) {
            $command = $namespace.str_replace(['/', '.php'], ['\\', ''], Str::after($command->getRealPath(), $searchPath));

            if (is_subclass_of($command, Command::class) &&
                ! (new ReflectionClass($command))->isAbstract()) {
                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }
}

/**
 * table prefix
 */
if (! function_exists('withTablePrefix')) {
    function withTablePrefix(string $table): string
    {
        return DB::connection()->getTablePrefix().$table;
    }
}

/**
 * get guard name
 */
if (! function_exists('getGuardName')) {
    function getGuardName(): string
    {
        $guardKeys = array_keys(config('catch.auth.guards'));

        if (count($guardKeys)) {
            return $guardKeys[0];
        }

        return 'admin';
    }
}

/**
 * get table columns
 */
if (! function_exists('getTableColumns')) {
    function getTableColumns(string $table): array
    {
        $SQL = 'desc '.withTablePrefix($table);

        $columns = [];

        foreach (DB::select($SQL) as $column) {
            $columns[] = $column->Field;
        }

        return $columns;
    }
}

if (! function_exists('dd_')) {
    /**
     * @param mixed ...$vars
     * @return never
     */
    function dd_(...$vars): never
    {
        if (! in_array(\PHP_SAPI, ['cli', 'phpdbg'], true) && ! headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');

        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        exit(1);
    }
}
