<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Route
 */
class Route extends Creator
{
    /**
     * @param string $controller
     */
    public function __construct(public readonly string $controller)
    {
    }

    /**
     * get file
     *
     * @return string
     */
    public function getFile(): string
    {
        return CatchAdmin::getModuleRoutePath($this->module);
    }

    /**
     * get content
     *
     * @return string
     */
    public function getContent(): string
    {
        // route 主要添加两个点
        // use Controller
        // 添加路由
        $route = Str::of('');

        $originContent = File::get(CatchAdmin::getModuleRoutePath($this->module));

        // 如果已经有 controller，就不再追加路由
        if (Str::of($originContent)->contains($this->getUserController())) {
            return $originContent;
        }

        File::lines(CatchAdmin::getModuleRoutePath($this->module))
            ->each(function ($line) use (&$route) {
                if (Str::of($line)->contains('Route::prefix')) {
                    $route = $route->trim(PHP_EOL)
                        ->newLine()
                        ->append($this->getUserController())
                        ->append(';')
                        ->newLine(2)
                        ->append($line)
                        ->newLine();
                } else {
                    $route = $route->append($line)->newLine();
                }
            });

        $apiResource = "Route::apiResource('{api}', {controller}::class);";

        return Str::of($route->toString())->replace(
            ['{module}', '//next'],
            [
                lcfirst($this->module),
                Str::of($apiResource)->replace(['{api}', '{controller}'], [$this->getApiString(), $this->getControllerName()])
                    ->prepend("\t")
                    ->prepend(PHP_EOL)
                    ->newLine()->append("\t//next")]
        )->toString();
    }

    /**
     * get api
     *
     * @return string
     */
    public function getApiString(): string
    {
        return Str::of($this->getControllerName())->remove('Controller')->snake('_')->replace('_', '/')->toString();
    }

    /**
     * get api route
     *
     * @return string
     */
    public function getApiRute(): string
    {
        return lcfirst($this->module).'/'.$this->getApiString();
    }

    /**
     * use controller
     *
     * @return string
     */
    protected function getUserController(): string
    {
        return 'use '.CatchAdmin::getModuleControllerNamespace($this->module).$this->getControllerName();
    }

    /**
     * get controller name
     *
     * @return string
     */
    protected function getControllerName(): string
    {
        return Str::of($this->controller)->whenContains('Controller', function ($value) {
            return Str::of($value)->ucfirst();
        }, function ($value) {
            return Str::of($value)->append('Controller')->ucfirst();
        })->toString();
    }
}
