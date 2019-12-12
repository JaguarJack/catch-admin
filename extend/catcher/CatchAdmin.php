<?php
namespace catcher;

use think\helper\Arr;

class CatchAdmin
{
    public const NAME = 'catchAdmin';

    /**
     *
     * @time 2019年11月30日
     * @return string
     */
    public static function directory(): string
    {
        return app()->getRootPath() . self::NAME . DIRECTORY_SEPARATOR;
    }

    /**
     *
     * @time 2019年12月04日
     * @param $module
     * @return string
     */
    public static function moduleDirectory($module): string
    {
        $directory =  self::directory() . $module . DIRECTORY_SEPARATOR;

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return $directory;
    }

    /**
     *
     * @time 2019年11月30日
     * @return string
     */
    public static function cacheDirectory(): string
    {
        $directory =  app()->getRuntimePath() . self::NAME . DIRECTORY_SEPARATOR;

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return $directory;
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
        return self::directory() . $module . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR. 'seeds' . DIRECTORY_SEPARATOR;
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
        $directory =  self::directory() . $module . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR;

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return $directory;
    }

    /**
     *
     * @time 2019年12月03日
     * @param $module
     * @return string
     */
    public static function getModuleModelDirectory($module): string
    {
        $directory =  self::directory() . $module . DIRECTORY_SEPARATOR . 'model' . DIRECTORY_SEPARATOR;

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }

        return $directory;
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
     * @return array
     */
    public static function getModulesInfo(): array
    {
        $modules = [];
        foreach (self::getModulesDirectory() as $module) {
            $moduleInfo = self::getModuleInfo($module);
            $modules[] = [
                'value' => $moduleInfo['alias'],
                'title' => $moduleInfo['name'],
            ];
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
     *
     * @time 2019年11月30日
     * @return array
     */
    protected static function getModuleViews(): array
    {
        $views = [];

        foreach (self::getModulesDirectory() as $module) {
            if (is_dir($module . 'view')) {
                $moduleInfo = self::getModuleInfo($module);
                $moduleName = $moduleInfo['alias'] ?? Arr::last(explode('/', $module));
                $views[$moduleName] = $module . 'view' . DIRECTORY_SEPARATOR;
            }
        }

        return $views;
    }

    /**
     * 获取模块信息
     *
     * @time 2019年11月30日
     * @param $module
     * @return mixed
     */
    public static function getModuleInfo($module)
    {
        if (file_exists($module . DIRECTORY_SEPARATOR . 'module.json')) {
            return \json_decode(file_get_contents($module . DIRECTORY_SEPARATOR . 'module.json'), true);
        }

        return [];
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
     * @return string
     */
    public static function getRoutes(): string
    {
        if (file_exists(self::getCacheRoutesFile())) {
            return self::getCacheRoutesFile();
        }

        self::cacheRoutes();

        return self::getCacheRoutesFile();
    }

    /**
     *
     * @time 2019年11月30日
     * @return array|mixed
     */
    public static function getViews()
    {
        if (file_exists(self::getCacheViewsFile())) {
            return self::getCacheViews();
        }

        return self::getModuleViews();
    }

    /**
     *
     * @time 2019年11月30日
     * @return false|int
     */
    public static function cacheRoutes()
    {
        $routeFiles = [];
        foreach (self::getModulesDirectory() as $module) {
            if (file_exists($module . 'route.php')) {
                $routeFiles[] = $module . 'route.php';
            }
        }
        $routes = '';
        foreach ($routeFiles as $route) {
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
            . var_export(self::getModuleServices(), true) . ';');
    }

    /**
     *
     * @time 2019年11月30日
     * @return false|int
     */
    public static function cacheViews()
    {
        return file_put_contents(self::getCacheViewsFile(), "<?php\r\n return "
            . var_export(self::getModuleViews(), true) . ';');
    }

    /**
     *
     * @time 2019年11月30日
     * @return mixed
     */
    protected static function getCacheViews()
    {
        return include self::getCacheViewsFile();
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
    protected static function getCacheViewsFile()
    {
        return self::cacheDirectory() . 'views.php';
    }

    /**
     *
     * @time 2019年11月30日
     * @return mixed
     */
    protected static function getCacheServicesFile()
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


