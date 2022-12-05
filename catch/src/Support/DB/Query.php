<?php

namespace Catch\Support\DB;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Query
{
    /**
     * @var string|null
     */
    protected static string|null $log = null;

    /**
     * @return void
     */
    public static function listen(): void
    {
        DB::listen(function ($query) {
            $sql = str_replace(
                '?',
                '%s',
                sprintf('[%s] '.$query->sql.' | %s ms'.PHP_EOL, date('Y-m-d H:i'), $query->time)
            );

            static::$log .= vsprintf($sql, $query->bindings);
        });
    }


    /**
     * @return void
     */
    public static function log(): void
    {
        if (static::$log) {
            $sqlLogPath = storage_path('logs'.DIRECTORY_SEPARATOR.'query'.DIRECTORY_SEPARATOR);

            if (! File::isDirectory($sqlLogPath)) {
                File::makeDirectory($sqlLogPath, 0777, true);
            }

            $logFile = $sqlLogPath.date('Ymd').'.log';

            if (! File::exists($logFile)) {
                File::put($logFile, '', true);
            }

            file_put_contents($logFile, static::$log.PHP_EOL, LOCK_EX | FILE_APPEND);

            static::$log = null;
        }
    }
}
