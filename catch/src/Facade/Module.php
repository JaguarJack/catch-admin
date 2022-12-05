<?php

declare(strict_types=1);

namespace Catch\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static all()
 * @method static create(array $module)
 * @method static update(string $name, array $module)
 * @method static delete(string $name)
 * @method static disOrEnable(string $name)
 *
 * @see ModuleRepository
 * Class Module
 */
class Module extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'module';
    }
}
