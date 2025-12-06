<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\Base\CatchController;
use Catch\CatchAdmin;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpFile;

class Controller extends Creator
{
    public function __construct(
        public readonly string $controller,
        public readonly string $model,
        public readonly ?string $request = null,
        public readonly bool $needForm = true,
        public readonly bool $dynamic = false,
        public readonly array $structures = [],
        public readonly array $operations = [],
    ) {}

    /**
     * get file
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
        return CatchAdmin::getModuleControllerPath($this->module).$this->getControllerName().$this->ext;
    }

    /**
     * @throws FileNotFoundException
     */
    public function getContent(): string|bool|PhpFile
    {
        $file = new PhpFile;
        $file->setStrictTypes();

        $request = $this->getRequest();
        $requestBaseName = class_basename($this->getRequest());
        $namespace = $file->addNamespace($this->getControllerNamespace());
        $namespace->addUse(CatchController::class, 'Controller')
            ->addUse($this->getModel())
            ->addUse($request)
            ->addUse(\Illuminate\Http\Request::class);

        // 动态表单
        if ($this->dynamic) {
            $namespace->addUse($this->getDynamicNamespace(), 'Dynamic');
        }

        $controller = $namespace->addClass($this->getControllerName())
            ->setExtends('Controller')
            ->addComment('@class '.$this->getControllerName());

        $controller->addMethod('__construct')
            ->addComment('@param '.$this->model.' $model')
            ->addPromotedParameter('model')->setType($this->model)
            ->setProtected()->setReadOnly();

        $controller->addMethod('index')->setBody('return $this->model->getList();')->setReturnType('mixed')
            ->addComment('列表'.PHP_EOL)
            ->addComment('@return mixed');

        if ($this->needForm) {
            $controller->addMethod('store')
                ->addComment('保存数据'.PHP_EOL)
                ->addComment('@param '.$requestBaseName.' $request')
                ->addComment('@return mixed')
                ->setBody('return $this->model->storeBy($request->all());')->setReturnType('mixed')
                ->addParameter('request')
                ->setType($requestBaseName);

            $controller->addMethod('show')
                ->addComment('展示数据'.PHP_EOL)
                ->addComment('@param mixed $id')
                ->addComment('@return mixed')
                ->setBody('return $this->model->firstBy($id, columns: $this->model->getForm());')->setReturnType('mixed')
                ->addParameter('id')->setType('mixed');

            $updateMethod = $controller->addMethod('update')
                ->addComment('更新数据'.PHP_EOL)
                ->addComment('@param mixed $id')
                ->addComment('@param '.$requestBaseName.' $request')
                ->addComment('@return mixed')
                ->setBody('return $this->model->updateBy($id, $request->all());')->setReturnType('mixed');
            $updateMethod->addParameter('id')->setType('mixed');
            $updateMethod->addParameter('request')->setType($requestBaseName);
        }

        $controller->addMethod('destroy')
            ->addComment('删除数据'.PHP_EOL)
            ->addComment('@param mixed $id')
            ->addComment('@return mixed')
            ->setBody('return $this->model->deleteBy($id);')->setReturnType('mixed')
            ->addParameter('id')->setType('mixed');

        // 导出方法
        if (in_array('export', $this->operations)) {

            $createExport = new Export($this->module, $this->getModel(), $this->structures);
            $enumFields = $this->enumsFields($this->structures);
            $fields = $this->getFieldsInList($this->structures);
            foreach ($fields as $k => $field) {
                foreach ($enumFields as $enumField) {
                    if ($enumField['field'] === $field) {
                        $fields[$k] = $enumField['field_text'];
                    }
                }
            }
            if ($createExport->create()) {
                $exportClass = $createExport->getExportClass();
                $namespace->addUse($exportClass);
                $importMethod = $controller->addMethod('export')
                    ->addComment('导入'.PHP_EOL)
                    ->addComment('@param Request $request')
                    ->addComment('@param '.class_basename($exportClass).' $export')
                    ->addComment('@return mixed')
                    ->addBody('// 导出')
                    ->addBody('return $export->download();')
                    ->setReturnType('mixed');

                $importMethod->addParameter('request')->setType('Request');
                $importMethod->addParameter('export')->setType(class_basename($exportClass));
            }

        }
        // 导入方法
        if (in_array('import', $this->operations)) {
            $createImport = new Import($this->module, $this->getModel(), $this->structures);
            if ($createImport->create()) {
                $createImport->createExportTemplateFile();
                $importClass = $createImport->getImportClass();
                $namespace->addUse($importClass);
                $importClass = $createImport->getImportClass();
                $namespace->addUse($importClass);
                $importMethod = $controller->addMethod('import')
                    ->addComment('导入'.PHP_EOL)
                    ->addComment('@param Request $request')
                    ->addComment('@param '.class_basename($importClass).' $export')
                    ->addComment('@return mixed')
                    ->addBody('// 导入')
                    ->addBody('return $import->import($request->file(\'file\'));')
                    ->setReturnType('mixed');

                $importMethod->addParameter('request')->setType('Request');
                $importMethod->addParameter('import')->setType(class_basename($importClass));
            }
        }

        // 字段切换
        $switchFields = $this->getSwitchFields($this->structures);
        if (count($switchFields)) {
            if (count($switchFields) === 1 && $switchFields[0] === 'status') {
                $controller->addMethod('enable')
                    ->addComment('状态切换'.PHP_EOL)
                    ->addComment('@param mixed $id')
                    ->addComment('@return mixed')
                    ->setBody('return $this->model->toggleBy($id);')->setReturnType('mixed')
                    ->addParameter('id')->setType('mixed');
            } else {
                $method = $controller->addMethod('enable')
                    ->addComment('字段切换'.PHP_EOL)
                    ->addComment('@param mixed $id')
                    ->addComment('@param Request $request')
                    ->addComment('@return mixed')
                    ->addBody('$field = $request->get(\'field\');'."\n")
                    ->addBody('return $this->model->toggleBy($id, $field);')->setReturnType('mixed');

                $method->addParameter('id')->setType('mixed');
                $method->addParameter('request')->setType('Request');
            }
        }

        if ($this->dynamic) {
            $controller->addMethod('dynamic')
                ->setBody('return $dynamic();')
                ->addComment('@param Dynamic $dynamic')
                ->addComment('@return mixed')
                ->setReturnType('mixed')
                ->addParameter('dynamic')->setType('Dynamic');

        }

        return $file;
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

    protected function getModel(): string
    {
        return CatchAdmin::getModuleModelNamespace($this->module).$this->model;
    }

    protected function getRequest(): string
    {
        return $this->request ? CatchAdmin::getModuleRequestNamespace($this->module).$this->request : 'Illuminate\Http\Request';
    }

    /**
     * get controller namespace
     */
    protected function getControllerNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleControllerNamespace($this->module))->rtrim('\\')->toString();
    }

    protected function getDynamicNamespace(): string
    {
        return CatchAdmin::getModuleNamespace($this->module).'Dynamics\\'.$this->controller;
    }
}
