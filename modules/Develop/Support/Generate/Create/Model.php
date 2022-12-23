<?php

// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2021 https://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/JaguarJack/catchadmin-laravel/blob/master/LICENSE.md )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
declare(strict_types=1);

namespace Modules\Develop\Support\Generate\Create;

use Catch\CatchAdmin;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends Creator
{
    protected array $replace = [
        '{uses}',
        '{property}',
        '{namespace}',
        '{model}',
        '{traits}',
        '{table}',
        '{fillable}',
        '{searchable}',
        '{fieldsInList}',
        '{isPaginate}', '{form}'
    ];

    protected array $structures;

    protected bool $softDelete;

    /**
     * @param string $modelName
     * @param string $tableName
     * @param bool $isPaginate
     */
    public function __construct(
        protected string $modelName,
        protected readonly string $tableName,
        protected readonly bool $isPaginate
    ) {
        $model = new class () extends EloquentModel {
            use SoftDeletes;
        };

        $this->softDelete = in_array($model->getDeletedAtColumn(), SchemaFacade::getColumnListing($this->tableName));
    }

    /**
     * get file
     *
     * @return string
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
        return CatchAdmin::getModuleModelPath($this->module).$this->getModelName().$this->ext;
    }

    /**
     * get content
     *
     * @return string
     */
    public function getContent(): string
    {
        $modelStub = File::get($this->getModelStub());


        return Str::of($modelStub)->replace($this->replace, [$this->getUses(),
            $this->getProperties(),
            $this->getModelNamespace(),
            $this->getModelName(),
            $this->getTraits(),
            $this->tableName,
            $this->getFillable(),
            $this->getSearchable(),
            $this->getFieldsInList(),
            $this->isPaginate(),
            $this->getInForm()
        ])->toString();
    }

    /**
     * get model namespace
     *
     * @return string
     */
    public function getModelNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleModelNamespace($this->module))->trim('\\')->append(';')->toString();
    }

    /**
     * get model name
     *
     * @return string
     */
    public function getModelName(): string
    {
        $modelName = Str::of($this->modelName);

        if (! $modelName->length()) {
            $modelName = Str::of($this->tableName)->camel();
        }

        return $modelName->ucfirst()->toString();
    }

    /**
     * get uses
     *
     * @return string
     */
    protected function getUses(): string
    {
        if (! $this->softDelete) {
            return <<<Text
use Catch\Traits\DB\BaseOperate;
use Catch\Traits\DB\ScopeTrait;
use Catch\Traits\DB\Trans;
use Illuminate\Database\Eloquent\Model;
Text;
        } else {
            return <<<Text
use Catch\Base\CatchModel as Model;
Text;
        }
    }

    /**
     * get traits
     *
     * @return string
     */
    protected function getTraits(): string
    {
        return $this->softDelete ? '' : 'use BaseOperate, Trans, ScopeTrait;';
    }

    /**
     *
     * @return string
     */
    protected function getProperties(): string
    {
        $comment = Str::of('/**')->newLine();

        foreach ($this->getTableColumns() as $column) {
            $comment = $comment->append(sprintf(' * @property $%s', $column))->newLine();
        }

        return $comment->append('*/')->toString();
    }

    /**
     * get fillable
     *
     * @return string
     */
    protected function getFillable(): string
    {
        $fillable = Str::of('');

        foreach ($this->getTableColumns() as $column) {
            $fillable = $fillable->append(" '{$column}'")->append(',');
        }

        return $fillable->rtrim(',')->toString();
    }


    /**
     *
     * @return array
     */
    protected function getTableColumns(): array
    {
        return getTableColumns($this->tableName);
    }

    /**
     * get field in list
     *
     * @return string
     */
    protected function getFieldsInList(): string
    {
        $str = Str::of('');
        foreach ($this->structures as $structure) {
            if ($structure['list']) {
                $str = $str->append("'{$structure['field']}'")->append(',');
            }
        }

        return $str->rtrim(',')->toString();
    }

    /**
     * get field in list
     *
     * @return string
     */
    protected function getInForm(): string
    {
        $str = Str::of('');
        foreach ($this->structures as $structure) {
            if ($structure['form']) {
                $str = $str->append("'{$structure['field']}'")->append(',');
            }
        }

        return $str->rtrim(',')->toString();
    }

    /**
     * searchable
     *
     * @return string
     */
    protected function getSearchable(): string
    {
        $searchable = Str::of('');

        foreach ($this->structures as $structure) {
            if ($structure['search'] && $structure['field'] && $structure['search_op']) {
                $searchable = $searchable->append(sprintf("'%s' => '%s'", $structure['field'], $structure['search_op']))->append(',')->newLine();
            }
        }

        return $searchable->toString();
    }

    /**
     * @return string
     */
    protected function isPaginate(): string
    {
        return $this->isPaginate ? '' : Str::of('protected bool $isPaginate = false;')->toString();
    }

    /**
     * @param array $structures
     * @return $this
     */
    public function setStructures(array $structures): static
    {
        $this->structures = $structures;

        return $this;
    }

    /**
     * get stub
     *
     * @return string
     */
    protected function getModelStub(): string
    {
        return dirname(__DIR__).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'model.stub';
    }
}
