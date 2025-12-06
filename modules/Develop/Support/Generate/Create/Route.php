<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Openapi\Exceptions\FailedException;

/**
 * Route
 */
class Route extends Creator
{
    public function __construct(public readonly string $controller)
    {
    }

    /**
     * get file
     */
    public function getFile(): string
    {
        return CatchAdmin::getModuleRoutePath($this->module);
    }

    /**
     * get content
     */
    public function getContent(): string
    {
        // route 主要添加两个点
        // use Controller
        // 添加路由
        $route = Str::of('');

        $originContent = $this->getOriginContent();

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

        $apiResource = "Route::adminResource('{api}', {controller}::class);";

        $replaceStr = Str::of($apiResource)->replace(['{api}', '{controller}'], [$this->getApiString(), $this->getControllerName()])
            ->prepend("\t")
            ->prepend(PHP_EOL)
            ->newLine()
            ->append("\t//next");


        if ($route->contains('//next')) {
            return $route->replace(
                ['{module}', '//next'],
                [
                    lcfirst($this->module),
                    $replaceStr,
                ]
            )->toString();
        }

        if ($route->contains('// next')) {
            return $route->replace(
                ['{module}', '// next'],
                [
                    lcfirst($this->module),
                    $replaceStr,
                ]
            )->toString();
        }

        throw new FailedException('路由写入失败，请检查');
    }

    public function getOriginContent(): string
    {
        return File::get(CatchAdmin::getModuleRoutePath($this->module));
    }

    public function putOriginContent(string $content): bool|int
    {
        return File::put(CatchAdmin::getModuleRoutePath($this->module), $content);
    }

    /**
     * get api
     */
    public function getApiString(): string
    {
        return Str::of($this->getControllerName())->remove('Controller')->snake('_')->replace('_', '/')->toString();
    }

    /**
     * get api route
     */
    public function getApiRoute(): string
    {
        return lcfirst($this->module).'/'.$this->getApiString();
    }

    /**
     * use controller
     */
    protected function getUserController(): string
    {
        return 'use '.CatchAdmin::getModuleControllerNamespace($this->module).$this->getControllerName();
    }

    /**
     * get controller name
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
