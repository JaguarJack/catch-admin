<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Controller extends Creator
{
    /**
     * @var array
     */
    protected array $replace = [
        '{namespace}', '{uses}', '{controller}', '{model}', '{request}'
    ];

    /**
     * @param string $controller
     * @param string $model
     * @param string|null $request
     */
    public function __construct(
        public readonly string $controller,
        public readonly string $model,
        public readonly ?string $request = null
    ) {
    }

    /**
     * get file
     *
     * @return string
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
        return CatchAdmin::getModuleControllerPath($this->module).$this->getControllerName().$this->ext;
    }

    public function getContent(): string|bool
    {
        // TODO: Implement getContent() method.
        return Str::of(File::get($this->getControllerStub()))->replace($this->replace, [
            $this->getControllerNamespace(),

            $this->getUses(),

            $this->getControllerName(),

            $this->model,

            $this->request ?: 'Request'
        ])->toString();
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

    /**
     * get uses
     *
     * @return string
     */
    protected function getUses(): string
    {
        return Str::of('use ')
            ->append(CatchAdmin::getModuleModelNamespace($this->module).$this->model)
            ->append(';')
            ->newLine()
            ->append('use ')
            ->when($this->request, function ($str) {
                return $str->append(CatchAdmin::getModuleRequestNamespace($this->module).$this->request);
            }, function ($str) {
                return $str->append("Illuminate\Http\Request");
            })->append(';')->newLine()->toString();
    }

    /**
     * get controller stub
     *
     * @return string
     */
    protected function getControllerStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'controller.stub';
    }

    /**
     * get controller namespace
     *
     * @return string
     */
    protected function getControllerNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleControllerNamespace($this->module))->rtrim('\\')->append(';')->toString();
    }
}
