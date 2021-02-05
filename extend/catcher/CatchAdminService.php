<?php
declare(strict_types=1);

namespace catcher;

use catcher\event\LoadModuleRoutes;
use think\exception\Handle;
use think\facade\Validate;
use think\Service;

class CatchAdminService extends Service
{
    /**
     *
     * @time 2019年11月29日
     * @return void
     */
    public function boot()
    {}

    /**
     * register
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/30
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerValidates();
        $this->registerMiddleWares();
        $this->registerEvents();
        $this->registerQuery();
        $this->registerExceptionHandle();
        $this->registerRoutePath();
        $this->registerServices();
    }

    /**
     *
     * @time 2019年12月13日
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $catchConsole = new CatchConsole($this->app);

            $this->app->bind('catch\console', $catchConsole);

            $this->commands($catchConsole->defaultCommands());
        }
    }
    /**
     *
     * @time 2019年12月07日
     * @return void
     */
    protected function registerValidates(): void
    {
        $validates = config('catch.validates');

        Validate::maker(function($validate) use ($validates) {
            foreach ($validates as $vali) {
                $vali = app()->make($vali);
                $validate->extend($vali->type(), [$vali, 'verify'], $vali->message());
            }
        });
    }

    /**
     *
     * @time 2019年12月12日
     * @return void
     */
    protected function registerMiddleWares(): void
    {
      // todo
    }

    /**
     * 注册监听者
     *
     * @time 2019年12月12日
     * @return void
     */
    protected function registerEvents(): void
    {
        $this->app->event->listenEvents([
            'RouteLoaded' => [LoadModuleRoutes::class]
        ]);
    }

  /**
   * register query
   *
   * @time 2020年02月20日
   * @return void
   */
    protected function registerQuery(): void
    {
        $connections = $this->app->config->get('database.connections');

        // 支持多数据库配置注入 Query
        foreach ($connections as &$connection) {
            $connection['query'] = CatchQuery::class;
        }

        $this->app->config->set([
          'connections' => $connections
        ], 'database');
    }

  /**
   * register exception
   *
   * @time 2020年02月20日
   * @return void
   */
    protected function registerExceptionHandle(): void
    {
        $this->app->bind(Handle::class, CatchExceptionHandle::class);
    }

    /**
     * 注册模块服务
     *
     * @time 2020年06月23日
     * @return void
     */
    protected function registerServices()
    {
        $services = file_exists(CatchAdmin::getCacheServicesFile()) ?
            include CatchAdmin::getCacheServicesFile() :
            CatchAdmin::getEnabledService();

        foreach ($services as $service) {
            if (class_exists($service)) {
                $this->app->register($service);
            }
        }
    }

    /**
     * 注册路由地址
     *
     * @time 2020年06月23日
     * @return void
     */
    protected function registerRoutePath()
    {
        $this->app->instance('routePath', new class {
            protected $path = [];
            public function loadRouterFrom($path)
            {
                $this->path[] = $path;
                return $this;
            }
            public function get()
            {
                return $this->path;
            }
        });
    }
}
