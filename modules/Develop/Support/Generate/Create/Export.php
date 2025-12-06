<?php

declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nette\PhpGenerator\PhpFile;

/**
 * 创建导入文件
 */
class Export extends Creator
{
    public function __construct(
        protected string $module,
        protected string $model,
        protected array $structures
    ) {}

    public function getFile(): string
    {
        CatchAdmin::makeDir(CatchAdmin::getModulePath($this->module).DIRECTORY_SEPARATOR.'Excel'.DIRECTORY_SEPARATOR.'Export');

        return CatchAdmin::getModulePath($this->module).$this->getExportName().$this->ext;
    }

    /**
     * get content
     */
    public function getContent(): string|bool|PhpFile
    {
        $file = new PhpFile;
        $file->setStrictTypes();

        $labels = $fields = [];

        $enumFields = $this->enumsFields($this->structures);
        foreach ($this->structures as $structure) {
            if ($structure['export']) {
                $labels[] = $structure['label'] ?: $structure['field'];
                $fields[] = $structure['field'];
            }
        }

        $isHasEnumFields = false;
        foreach ($fields as $k => $field) {
            foreach ($enumFields as $enumField) {
                if ($enumField['field'] === $field) {
                    $isHasEnumFields = true;
                    $fields[$k] = $enumField['field_text'];
                }
            }
        }

        $namespace = $file->addNamespace($this->getNamespace());
        $namespace->addUse(\Catch\Support\Excel\Export::class);
        $namespace->addUse(Collection::class);
        $namespace->addUse($this->model);

        $modelBaseName = class_basename($this->model);
        $class = $namespace->addClass(class_basename($this->getExportName()))->setExtends('Export');

        $class->addComment('导出数据')
            ->addComment("\n")
            ->addComment('@class '.$this->getExportName());

        $class->addProperty('header', $labels)->setType('array')->setProtected();

        // 如果有枚举字段
        if ($isHasEnumFields) {
            $class->addMethod('array')
                ->setReturnType('array')
                ->addBody('return '.$modelBaseName.'::query()->get()->select(?)->toArray();', [$fields])
                ->addComment('@return array');
        } else {
            $class->addMethod('array')
                ->setReturnType('array')
                ->addBody('return '.$modelBaseName.'::query()->select(?)->get()->toArray();', [$fields])
                ->addComment('@return array');
        }

        return $file;
    }

    /**
     * get namespace
     */
    protected function getNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleNamespace($this->module))->append('Excel\Export')->toString();
    }

    /**
     * get request name
     *
     * @return ?string
     */
    public function getExportName(): ?string
    {
        return Str::of('Excel'.DIRECTORY_SEPARATOR.'Export'.DIRECTORY_SEPARATOR)->append(
            Str::of(class_basename($this->model))->remove('Model')->append('Export')->ucfirst()->toString()
        )->toString();
    }

    /**
     * @return string
     */
    public function getExportClass(): string
    {
        return $this->getNamespace().'\\'.class_basename($this->getExportName());
    }
}
