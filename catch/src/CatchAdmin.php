<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 ~ now https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

declare(strict_types=1);

namespace Catch;

use Catch\Support\Module\Installer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CatchAdmin
{
    public const VERSION = '0.1.0';

    /**
     * version
     *
     */
    public static function version(): string
    {
        return static::VERSION;
    }

    /**
     * module root path
     *
     * @return string
     */
    public static function moduleRootPath(): string
    {
        return self::makeDir(base_path(config('catch.module.root')).DIRECTORY_SEPARATOR);
    }

    /**
     * make dir
     *
     * @param string $dir
     * @return string
     */
    public static function makeDir(string $dir): string
    {
        if (! File::isDirectory($dir) && ! File::makeDirectory($dir, 0777, true)) {
            throw new \RuntimeException(sprintf('Directory %s created Failed', $dir));
        }

        return $dir;
    }

    /**
     * module dir
     *
     * @param string $module
     * @param bool $make
     * @return string
     */
    public static function getModulePath(string $module, bool $make = true): string
    {
        if ($make) {
            return self::makeDir(self::moduleRootPath().ucfirst($module).DIRECTORY_SEPARATOR);
        }

        return self::moduleRootPath().ucfirst($module).DIRECTORY_SEPARATOR;
    }

    /**
     * delete module path
     *
     * @param string $module
     * @return bool
     */
    public static function deleteModulePath(string $module): bool
    {
        if (self::isModulePathExist($module)) {
            File::deleteDirectory(self::getModulePath($module));
        }

        return true;
    }

    /**
     * module path exists
     *
     * @param string $module
     * @return bool
     */
    public static function isModulePathExist(string $module): bool
    {
        return File::isDirectory(self::moduleRootPath().ucfirst($module).DIRECTORY_SEPARATOR);
    }

    /**
     * module migration dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleMigrationPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR);
    }

    /**
     * module seeder dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleSeederPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'database'.DIRECTORY_SEPARATOR.'seeder'.DIRECTORY_SEPARATOR);
    }

    /**
     * get modules dir
     *
     * @return array
     */
    public static function getModulesPath(): array
    {
        return File::directories(self::moduleRootPath());
    }

    /**
     * get module root namespace
     *
     * @return string
     */
    public static function getModuleRootNamespace(): string
    {
        return config('catch.module.namespace').'\\';
    }

    /**
     * get module root namespace
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleNamespace($moduleName): string
    {
        return self::getModuleRootNamespace().ucfirst($moduleName).'\\';
    }

    /**
     * model namespace
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleModelNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName).'Models\\';
    }

    /**
     * getServiceProviders
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleServiceProviderNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName).'Providers\\';
    }

    /**
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleServiceProvider($moduleName): string
    {
        return self::getModuleServiceProviderNamespace($moduleName).ucfirst($moduleName).'ServiceProvider';
    }

    /**
     * controller namespace
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleControllerNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName).'Http\\Controllers\\';
    }

    /**
     * getModuleRequestNamespace
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleRequestNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName).'Http\\Requests\\';
    }

    /**
     * getModuleRequestNamespace
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleEventsNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName).'Events\\';
    }

    /**
     * getModuleRequestNamespace
     *
     * @param $moduleName
     * @return string
     */
    public static function getModuleListenersNamespace($moduleName): string
    {
        return self::getModuleNamespace($moduleName).'Listeners\\';
    }


    /**
     * module provider dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleProviderPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Providers'.DIRECTORY_SEPARATOR);
    }

    /**
     * module model dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleModelPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Models'.DIRECTORY_SEPARATOR);
    }

    /**
     * module controller dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleControllerPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Http'.DIRECTORY_SEPARATOR.'Controllers'.DIRECTORY_SEPARATOR);
    }

    /**
     * module request dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleRequestPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Http'.DIRECTORY_SEPARATOR.'Requests'.DIRECTORY_SEPARATOR);
    }

    /**
     * module request dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleEventPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Events'.DIRECTORY_SEPARATOR);
    }

    /**
     * module request dir
     *
     * @param string $module
     * @return string
     */
    public static function getModuleListenersPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Listeners'.DIRECTORY_SEPARATOR);
    }

    /**
     * commands path
     *
     * @param string $module
     * @return string
     */
    public static function getCommandsPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'Commands'.DIRECTORY_SEPARATOR);
    }

    /**
     * commands namespace
     *
     * @param string $module
     * @return string
     */
    public static function getCommandsNamespace(string $module): string
    {
        return self::getModuleNamespace($module).'Commands\\';
    }


    /**
     * module route
     *
     * @param string $module
     * @param string $routeName
     * @return string
     */
    public static function getModuleRoutePath(string $module, string $routeName = 'route.php'): string
    {
        return self::getModulePath($module).$routeName;
    }

    /**
     * module route.php exists
     *
     * @param string $module
     * @return bool
     */
    public static function isModuleRouteExists(string $module): bool
    {
        return File::exists(self::getModuleRoutePath($module));
    }

    /**
     * module views path
     *
     * @param string $module
     * @return string
     */
    public static function getModuleViewsPath(string $module): string
    {
        return self::makeDir(self::getModulePath($module).'views'.DIRECTORY_SEPARATOR);
    }

    /**
     * relative path
     *
     * @param $path
     * @return string
     */
    public static function getModuleRelativePath($path): string
    {
        return Str::replaceFirst(base_path(), '.', $path);
    }

    /**
     *
     * @param string $module
     * @return Installer
     */
    public static function getModuleInstaller(string $module): Installer
    {
        $installer = self::getModuleServiceProviderNamespace($module).'Installer';

        if (class_exists($installer)) {
            return app($installer);
        }

        throw new \RuntimeException("Installer [$installer] Not Found");
    }
}
