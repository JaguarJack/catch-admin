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
class Import extends Creator
{
    protected array $labels = [];
    public function __construct(
        protected string $module,
        protected string $model,
        protected array $structures
    ) {}

    public function getFile(): string
    {
        CatchAdmin::makeDir(CatchAdmin::getModulePath($this->module).DIRECTORY_SEPARATOR.'Excel'.DIRECTORY_SEPARATOR.'Import');

        return CatchAdmin::getModulePath($this->module).$this->getImportName().$this->ext;
    }

    /**
     * get content
     */
    public function getContent(): string|bool|PhpFile
    {
        $file = new PhpFile;
        $file->setStrictTypes();

        $fields = [];
        foreach ($this->structures as $structure) {
            if ($structure['import']) {
                $this->labels[] = $structure['label'] ?: $structure['field'];
                $fields[] = $structure['field'];
            }
        }

        $namespace = $file->addNamespace($this->getNamespace());
        $namespace->addUse(\Catch\Support\Excel\Import::class);
        $namespace->addUse(Collection::class);
        $namespace->addUse($this->model);

        $modelBaseName = class_basename($this->model);
        $class = $namespace->addClass(class_basename($this->getImportName()))->setExtends('Import')
            ->addComment('导入数据')
            ->addComment("\n")
            ->addComment('@class '.$this->getImportName());

        $functionLike = $class->addMethod('collection')
            ->setReturnType('void')
            ->addBody('$rows->each(function ($row) {')
            ->addBody('$model = new '.$modelBaseName.';');
        foreach ($fields as $key => $field) {
            $functionLike = $functionLike->addBody('$model->'.$field.' = $row['.$key.'];');
        }
        $functionLike->addBody('$model->save();')
            ->addBody('});')
            ->addComment('导入数据')
            ->addComment("\n")
            ->addComment('@param Collection $rows')
            ->addComment('@return void')
            ->addParameter('rows')->setType('Collection');

        return $file;
    }

    /**
     * get namespace
     */
    protected function getNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleNamespace($this->module))->append('Excel\Import')->toString();
    }

    /**
     * get request name
     *
     * @return ?string
     */
    public function getImportName(): ?string
    {
        return Str::of('Excel'.DIRECTORY_SEPARATOR.'Import'.DIRECTORY_SEPARATOR)->append(
            Str::of(class_basename($this->model))->remove('Model')->append('Import')->ucfirst()->toString()
        )->toString();
    }

    /**
     * @return string
     */
    public function getImportClass(): string
    {
        return $this->getNamespace().'\\'.class_basename($this->getImportName());
    }

    /**
     * 创建导入模板
     *
     * @return void
     */
    public function createExportTemplateFile(): void
    {
        $importFile = new class extends \Catch\Support\Excel\Export
        {
            public function array(): array
            {
                // TODO: Implement array() method.
                return [];
            }
        };

        $importFile->setHeader(array_unique($this->labels))
            ->setPath('importTemplates')
            ->setFilename(class_basename($this->model).'导入模板.xlsx')
            ->export('static');
    }
}
