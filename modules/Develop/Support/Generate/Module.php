<?php

namespace Modules\Develop\Support\Generate;

use Catch\CatchAdmin;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Module
{
    public function __construct(
        public string $module,
        protected bool $controller,
        protected bool $models,
        protected bool $requests,
        protected bool $database,
        protected string $title,
        protected string $keywords,
        protected string $description,
    ) {
    }

    /**
     * create
     */
    public function create(): void
    {
        if ($this->controller) {
            CatchAdmin::getModuleControllerPath($this->module);
        }

        if ($this->models) {
            CatchAdmin::getModuleModelPath($this->module);
        }

        if ($this->requests) {
            CatchAdmin::getModuleRequestPath($this->module);
        }

        if ($this->database) {
            CatchAdmin::getModuleMigrationPath($this->module);
            CatchAdmin::getModuleSeederPath($this->module);
        }

        $this->createProvider();

        $this->createRoute();

        $this->createInstaller();
    }

    /**
     * delete
     */
    public function delete(): void
    {
    }

    /**
     * create provider
     */
    protected function createProvider(): void
    {
        CatchAdmin::getModuleProviderPath($this->module);

        File::put(
            CatchAdmin::getModuleProviderPath($this->module).sprintf('%sServiceProvider.php', ucfirst($this->module)),
            Str::of(
                File::get(__DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'provider.stub')
            )->replace(['{Module}', '{module}'], [ucfirst($this->module), $this->module])
        );
    }

    /**
     * create route
     */
    protected function createRoute(): void
    {
        $content = Str::of(
            File::get(__DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'route.stub')
        )->replace(['{module}'], [lcfirst($this->module)]);

        File::put(
            CatchAdmin::getModuleRoutePath($this->module),
            $content
        );
    }

    protected function createInstaller(): void
    {
        $content = Str::of(
            File::get(__DIR__.DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'installer.stub')
        )->replace([
            '{Module}',
            '{name}',
            '{title}',
            '{path}',
            '{keywords}',
            '{description}',
            '{provider}',
        ], [ucfirst($this->module), lcfirst($this->module), $this->title,
            ucfirst($this->module), $this->keywords, $this->description, ucfirst($this->module)]);

        File::put(
            CatchAdmin::getModulePath($this->module).'Installer.php',
            $content
        );
    }
}
