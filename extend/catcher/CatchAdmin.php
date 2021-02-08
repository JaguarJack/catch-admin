<?php
declare(strict_types=1);

namespace catcher;

use catcher\facade\FileSystem;

class CatchAdmin
{
    public static $root = 'catch';

    public const VERSION = '2.5.0';


    /**
     *
     * @time 2019年11月30日
     * @return string
     */
    public static function directory(): string
    {
        return app()->getRootPath() . self::$root . DIRECTORY_SEPARATOR;
    }

    /**
     * 创建目录
     *
     * @time 2019年12月16日
     * @param string $directory
     * @return string
     */
    public static function makeDirectory(string $directory): string
    {
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return $directory;
    }

    /**
     *
     * @time 2019年12月04日
     * @param $module
     * @return string
     */
    public static function moduleDirectory($module): string
    {
        return self::makeDirectory(self::directory() . $module . DIRECTORY_SEPARATOR);
    }

    /**
     *
     * @time 2019年11月30日
     * @return string
     */
    public static function cacheDirectory(): string
    {
        return self::makeDirectory(app()->getRuntimePath() . self::$root . DIRECTORY_SEPARATOR);
    }

    /**
     * 备份地址
     *
     * @time 2019年12月13日
     * @return string
     */
    public static function backupDirectory(): string
    {
        return self::makeDirectory(self::cacheDirectory() . 'backup' .DIRECTORY_SEPARATOR);
    }

    /**
     *
     * @time 2019年12月03日
     * @param $module
     * @return string
     */
    public static function moduleMigrationsDirectory($module): string
    {
        return self::directory() . $module . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR. 'migrations' . DIRECTORY_SEPARATOR;
    }

    /**
     *
     * @time 2019年12月03日
     * @param $module
     * @return string
     */
    public static function moduleSeedsDirectory($module): string
    {
        $seedPath = self::directory() . $module . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR. 'seeds' . DIRECTORY_SEPARATOR;

        self::makeDirectory($seedPath);

        return  $seedPath;
    }

    /**
     * 获取模块 view path
     *
     * @time 2019年12月03日
     * @param $module
     * @return string
     */
    public static function getModuleViewPath($module): string
    {
        return self::makeDirectory(self::directory() . $module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR);
    }

    /**
     *
     * @time 2019年12月03日
     * @param $module
     * @return string
     */
    public static function getModuleModelDirectory($module): string
    {
        return self::makeDirectory(self::directory() . $module . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR);
    }
    /**
     *
     * @time 2019年11月30日
     * @return array
     */
    public static function getModulesDirectory(): array
    {
        $modules = glob(self::directory() . '*');

        foreach ($modules as $key => &$module) {
            if (!is_dir($module)) {
                unset($modules[$key]);
            }

            $module .= DIRECTORY_SEPARATOR;
        }

        return $modules;
    }

    /**
     *
     * @time 2019年12月12日
     * @param bool $select
     * @return array
     */
    public static function getModulesInfo($select = true): array
    {
        $modules = [];
        if ($select) {
            foreach (self::getModulesDirectory() as $module) {
                $moduleInfo = self::getModuleInfo($module);
                $modules[] = [
                    'value' => $moduleInfo['alias'],
                    'title' => $moduleInfo['name'],
                ];
            }
        } else {
            foreach (self::getModulesDirectory() as $module) {
                $moduleInfo = self::getModuleInfo($module);
                $modules[$moduleInfo['alias']] = $moduleInfo['name'];
            }
        }

        return $modules;
    }

    /**
     *
     * @time 2019年11月30日
     * @return array
     */
    protected static function getModuleServices(): array
    {
        $services = [];

        foreach (self::getModulesDirectory() as $module) {
            if (is_dir($module)) {
                $moduleInfo = self::getModuleInfo($module);
                if (isset($moduleInfo['services']) && !empty($moduleInfo['services'])) {
                    $services = array_merge($services, $moduleInfo['services']);
                }
            }
        }

        return $services;
    }

    /**
     * 获取可用模块
     *
     * @time 2020年06月23日
     * @return array
     */
    public static function getEnabledService(): array
    {
        $services = [];

        foreach (self::getModulesDirectory() as $module) {
            if (is_dir($module)) {
                $moduleInfo = self::getModuleInfo($module);
                // 如果没有设置 module.json 默认加载
                $moduleServices = $moduleInfo['services'] ?? [];
                if (!empty($moduleServices) && $moduleInfo['enable']) {
                    $services = array_merge($services, $moduleServices);
                }
            }
        }

        return $services;
    }

    /**
     * 获取模块 Json
     *
     * @time 2021年02月08日
     * @param $module
     * @return string
     */
    public static function getModuleJson($module): string
    {
        if (is_dir($module)) {
            return $module . DIRECTORY_SEPARATOR . 'module.json';
        }

        return self::moduleDirectory($module) . 'module.json';
    }

    /**
     * 获取模块信息
     *
     * @time 2021年02月08日
     * @param $module
     * @return array
     */
    public static function getModuleInfo($module): array
    {
        $moduleJson = self::getModuleJson($module);

        if (!file_exists($moduleJson)) {
            return [];
        }

        return \json_decode(FileSystem::sharedGet($moduleJson), true);
    }

    /**
     * 更新模块信息
     *
     * @time 2021年02月08日
     * @param $module
     * @param $info
     * @return bool
     */
    public static function updateModuleInfo($module, $info): bool
    {
        $moduleInfo = self::getModuleInfo($module);

        if (!count($moduleInfo)) {
            return false;
        }

        foreach ($moduleInfo as $k => $v) {
            if (isset($info[$k])) {
                $moduleInfo[$k] = $info[$k];
            }
        }

        if (! is_writeable(self::getModuleJson($module))) {
            chmod(self::getModuleJson($module), 666);
        }

        FileSystem::put(self::getModuleJson($module), \json_encode($moduleInfo, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));

        return true;
    }

    /**
     * 获取服务
     *
     * @time 2019年11月30日
     * @return array
     */
    public static function getServices(): array
    {
        if (file_exists(self::getCacheServicesFile())) {
            return self::getCacheServices();
        }

        return self::getModuleServices();
    }

    /**
     *
     * @time 2019年11月30日
     * @return mixed
     */
    public static function getRoutes(): array
    {
        if (file_exists(self::getCacheRoutesFile())) {
            return [self::getCacheRoutesFile()];
        }

        return self::getModuleRoutes();
    }


    /**
     *
     * @time 2019年12月15日
     * @return array
     */
    public static function getModuleRoutes(): array
    {
        $routeFiles = [];
        foreach (self::getModulesDirectory() as $module) {
            $moduleInfo = self::getModuleInfo($module);
            $moduleAlias = $moduleInfo['alias'] ?? '';
            if (!in_array($moduleAlias, ['login']) && file_exists($module . 'route.php')) {
                $routeFiles[] = $module . 'route.php';
            }
        }

        return $routeFiles;
    }

    /**
     *
     * @time 2019年11月30日
     * @return false|int
     */
    public static function cacheRoutes()
    {
        $routes = '';

        foreach (self::getModuleRoutes() as $route) {
            $routes .= trim(str_replace('<?php', '',  file_get_contents($route))) . PHP_EOL;
        }

        return file_put_contents(self::getCacheRoutesFile(), "<?php\r\n " . $routes);
    }

    /**
     *
     * @time 2019年11月30日
     * @return false|int
     */
    public static function cacheServices()
    {
        return file_put_contents(self::getCacheServicesFile(), "<?php\r\n return "
            . var_export(self::getEnabledService(), true) . ';');
    }

    /**
     *
     * @time 2019年11月30日
     * @return mixed
     */
    protected static function getCacheServices()
    {
        return include self::getCacheServicesFile();
    }

    /**
     *
     * @time 2019年11月30日
     * @return mixed
     */
    public static function getCacheServicesFile(): string
    {
        return self::cacheDirectory() . 'services.php';
    }

    /**
     *
     * @time 2019年11月30日
     * @return string
     */
    protected static function getCacheRoutesFile(): string
    {
        return self::cacheDirectory() . 'routes.php';
    }

}


