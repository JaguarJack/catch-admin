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

use Catch\Base\CatchModel;
use Catch\CatchAdmin;
use Catch\Traits\DB\BaseOperate;
use Catch\Traits\DB\DateformatTrait;
use Catch\Traits\DB\ScopeTrait;
use Catch\Traits\DB\Trans;
use Catch\Traits\DB\WithAttributes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema as SchemaFacade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nette\PhpGenerator\Dumper;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;

class Model extends Creator
{
    /**
     * 字段结构
     */
    protected array $structures;

    /**
     * 软删除
     */
    protected bool $softDelete;

    /**
     * 时间戳
     */
    protected bool $timestamps;

    public function __construct(
        protected string $modelName,
        protected readonly string $tableName,
        protected readonly bool $isPaginate,
        protected readonly array $relations = []
    ) {
        $model = new class() extends EloquentModel
        {
            use SoftDeletes;
        };

        $this->softDelete = in_array($model->getDeletedAtColumn(), SchemaFacade::getColumnListing($this->tableName));

        $this->timestamps = in_array($model->getCreatedAtColumn(), SchemaFacade::getColumnListing($this->tableName))
            && in_array($model->getUpdatedAtColumn(), SchemaFacade::getColumnListing($this->tableName));
    }

    /**
     * get file
     */
    public function getFile(): string
    {
        // TODO: Implement getFile() method.
        return CatchAdmin::getModuleModelPath($this->module) . $this->getModelName() . $this->ext;
    }

    /**
     * get content
     */
    public function getContent(): string|bool|PhpFile
    {
        $file = new PhpFile();
        $file->setStrictTypes();

        $namespace = $file->addNamespace($this->getModelNamespace());

        if ($this->softDelete) {
            $namespace->addUse(CatchModel::class, 'Model');
        } else {
            $namespace->addUse(\Illuminate\Database\Eloquent\Model::class);
            $namespace->addUse(DateformatTrait::class);
            $namespace->addUse(BaseOperate::class);
            $namespace->addUse(ScopeTrait::class);
            $namespace->addUse(Trans::class);
            $namespace->addUse(WithAttributes::class);
        }

        $namespace->addUse(Attribute::class); // 默认添加 Attribute

        $modelClass = $namespace->addClass($this->getModelName())->setExtends('Model');

        if (! $this->softDelete) {
            $modelClass->addTrait('DateformatTrait');
            $modelClass->addTrait('BaseOperate');
            $modelClass->addTrait('ScopeTrait');
            $modelClass->addTrait('Trans');
            $modelClass->addTrait('WithAttributes');
        }

        $tableColumns = $this->getTableColumns();
        // 添加属性
        foreach ($tableColumns as $column) {
            $phpType = $this->parseMysqlTypeToPhpType($column['type']);
            $modelClass->addComment('@property ' . ($column['nullable'] ? $phpType . '|null' : $phpType) . ' $' . $column['name'] . ' ' . $column['comment']);
        }

        // 添加表名
        $modelClass->addProperty('table', $this->tableName)->setProtected()->addComment('表名');

        if (! $this->softDelete) {
            $modelClass->addProperty('dateFormat', 'U')->setProtected()->addComment('时间格式');

            $modelClass->addProperty('timestamps', $this->timestamps)->addComment('自动填入时间戳');
        }

        // 添加 fillable
        $modelClass->addProperty('fillable', array_column($tableColumns, 'name'))->setProtected()->addComment('允许填充字段');

        // 添加 casts
        $dateCasts = [];
        foreach ($tableColumns as $column) {
            if (str_contains($column['type'], 'int')
                && (str_ends_with($column['name'], '_at') || str_ends_with($column['name'], '_time'))
                && ! in_array($column['name'], ['created_at', 'updated_at', 'deleted_at'])
            ) {
                $dateCasts[$column['name']] = 'datetime:Y-m-d H:i:s';
            }
        }
        if (count($dateCasts)) {
            $modelClass->addProperty('casts', $dateCasts)->setProtected()->addComment('casts');
        }
        // 添加 fields
        $modelClass->addProperty('fields', $this->getFieldsInList($this->structures))->setType('array')->setProtected()->addComment('列表显示字段');

        // 添加 form
        $modelClass->addProperty('form', $this->getInForm())->setType('array')->setProtected()->addComment('表单填充字段');

        $columnNames = array_column($tableColumns, 'name');
        $asTree = in_array('parent_id', $columnNames) || in_array('pid', $columnNames);

        // 是否关闭页码
        if (! $this->isPaginate || $asTree) {
            $modelClass->addProperty('isPaginate', ! $this->isPaginate)->setType('bool')->addComment('关闭分页');
        }

        // 添加搜索字段
        if ($this->softDelete) {
            if (count($this->getSearchable())) {
                $modelClass->addProperty('searchable', $this->getSearchable())->setType('array')->addComment('搜索字段');
            }
            if ($asTree) {
                $modelClass->addProperty('asTree', true)->setType('bool')->setProtected()->addComment('树形展示数据');
            }
        } else {
            $dumper = new Dumper();
            $body = 'parent::__construct();' . PHP_EOL;
            if (count($this->getSearchable())) {
                $body .= '$this->searchable = ' . $dumper->dump($this->getSearchable()) . ';' . PHP_EOL;
            }
            $body .= $asTree ? '$this->asTree = true;' : '';
            // 添加 construct 方法
            $modelClass->addMethod('__construct')->setBody($body);
        }
        // 处理关联关系
        $relationModels = $this->formatRelations($this->relations);
        if (count($relationModels)) {
            // foreach (array_column($relationModels, 'relatedModel') as $relationModel) {
            //  $namespace->addUse($relationModel);
            // }

            foreach (array_column($relationModels, 'relation_class') as $relationClass) {
                $namespace->addUse($relationClass);
            }

            foreach ($relationModels as $relationModel) {
                if (! isset($relationModel['relation'])) {
                    continue;
                }
                $relationClassName = class_basename($relationModel['relation_class']);
                // 关联关系相关参数
                $relationParams = [];
                // 处理这四个相关方法 hasOne/hasMany/belongsTo/belongsToMany
                if (in_array($relationModel['relation_method'], ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany'])) {
                    if (! isset($relationModel['relatedModel'])) {
                        continue;
                    }
                    $relationParams = ['related' => $this->addModelClass($namespace, $relationModel['relatedModel'])];
                    // hasMany/hasOne/belongsTo
                    if (! empty($relationModel['foreignKey'])) {
                        $relationParams['foreignKey'] = $relationModel['foreignKey'];
                    }
                    // hasMany/hasOne
                    if (! empty($relationModel['localKey'])) {
                        $relationParams['localKey'] = $relationModel['localKey'];
                    }
                    // belongsTo
                    if (! empty($relationModel['ownerKey'])) {
                        $relationParams['ownerKey'] = $relationModel['ownerKey'];
                    }
                    // belongsToMany 方法处理
                    if ($relationModel['relation_method'] === 'belongsToMany') {
                        // belongsToMany
                        if (! empty($relationModel['table'])) {
                            // 中间表如果使用 class 需要添加 class
                            $relationParams['table'] = $this->addModelClass($namespace, $relationModel['table']);
                        }
                        // belongsToMany
                        if (! empty($relationModel['foreignPivotKey'])) {
                            $relationParams['foreignPivotKey'] = $relationModel['foreignPivotKey'];
                        }
                        // belongsToMany
                        if (! empty($relationModel['relatedPivotKey'])) {
                            $relationParams['relatedPivotKey'] = $relationModel['relatedPivotKey'];
                        }
                        // belongsToMany
                        if (! empty($relationModel['parentKey'])) {
                            $relationParams['parentKey'] = $relationModel['parentKey'];
                        }
                        // belongsToMany
                        if (! empty($relationModel['relatedKey'])) {
                            $relationParams['relatedKey'] = $relationModel['relatedKey'];
                        }
                    }
                }

                // 远程一对多 一对一
                if (in_array($relationModel['relation_method'], ['hasOneThrough', 'hasManyThrough'])) {
                    $relationParams['related'] = $this->addModelClass($namespace, $relationModel['targetModel']);
                    // class_basename($relationModel['targetModel']);
                    $relationParams['through'] = $this->addModelClass($namespace, $relationModel['throughModel']);
                    // class_basename($relationModel['throughModel']);
                    if (! empty($relationModel['firstKey'])) {
                        $relationParams['firstKey'] = $relationModel['firstKey'];
                    }
                    if (! empty($relationModel['secondKey'])) {
                        $relationParams['secondKey'] = $relationModel['secondKey'];
                    }
                    if (! empty($relationModel['localKey'])) {
                        $relationParams['localKey'] = $relationModel['localKey'];
                    }
                    if (! empty($relationModel['secondLocalKey'])) {
                        $relationParams['secondLocalKey'] = $relationModel['secondLocalKey'];
                    }
                }

                $modelClass->addMethod($relationModel['relation'])
                    ->setReturnType($relationClassName)
                    ->addBody('return $this->' . $relationModel['relation_method'] . '(...?:);', [$relationParams])
                    ->addComment('@return ' . $relationClassName);
            }
        }

        // 处理枚举字段，包含 options 的字段
        $enumFields = $this->enumsFields($this->structures);
        if (count($enumFields)) {
            $modelClass->addProperty('appends', array_column($enumFields, 'field_text'))->setProtected()->addComment('追加字段');

            foreach ($enumFields as $enumField) {
                // 添加转换器
                $method = Str::of($enumField['field_text'])->camel()->lcfirst()->toString();
                $modelClass->addMethod($method)
                    ->setReturnType('Attribute')
                    ->addComment("{$enumField['field']} 字段转换器 \n")
                    ->addComment('@return Attribute')
                    ->addBody('$text = ?;' . "\n", [$enumField['options']])
                    ->addBody('return Attribute::make(get: fn ($value) => $text[$this->' . $enumField['field'] . '] ?? \'\');');
            }
        }

        // 如果有parent_id 或者 pid 字段，由于前端级联组件都是提交数组所以这里需要座一层转换
        if (in_array('parent_id', $columnNames)) {
            $modelClass->addMethod('parentId')
                ->setReturnType('Attribute')
                ->addComment("parent_id 字段转换器 \n")
                ->addComment('@return Attribute')
                ->addBody('return Attribute::make(set: fn ($value) => is_array($value) ? $value[count($value)-1] : $value);');
        }
        if (in_array('pid', $columnNames)) {
            $modelClass->addMethod('pid')
                ->setReturnType('Attribute')
                ->addComment("pid 字段转换器 \n")
                ->addComment('@return Attribute')
                ->addBody('return Attribute::make(set: fn ($value) => is_array($value) ? $value[count($value)-1] : $value);');
        }

        // 如果是 upload 组件，反显图片
        foreach ($this->structures as $structure) {
            if (! $structure['form'] && ! $structure['list']) {
                continue;
            }
            if ($structure['form_component'] == 'upload-image') {
                $namespace->addUse(Storage::class);
                $modelFuncLike = $modelClass->addMethod(Str::of($structure['field'])->camel()->lcfirst()->toString())
                    ->setReturnType('Attribute')
                    ->addComment("{$structure['field']} 图片转换 \n")
                    ->addComment('@return Attribute')
                    ->addBody('return Attribute::make(');

                if ($structure['list']) {
                    $modelFuncLike = $modelFuncLike->addBody('get: fn ($value) => $value ? url($value) : \'\',');
                }

                if ($structure['form']) {
                    $modelFuncLike = $modelFuncLike->addBody('set: fn ($value) => remove_app_url($value)');
                }
                $modelFuncLike->addBody(');');
            }

            if ($structure['form_component'] == 'upload-images') {
                $namespace->addUse(Storage::class);
                $modelFuncLike = $modelClass->addMethod(Str::of($structure['field'])->camel()->lcfirst()->toString())
                    ->setReturnType('Attribute')
                    ->addComment("{$structure['field']} 转换 \n")
                    ->addComment('@return Attribute')
                    ->addBody('return Attribute::make(');

                if ($structure['list']) {
                    $modelFuncLike = $modelFuncLike->addBody('get: fn ($value) => ! $value ? [] : array_map(fn ($item) => $item ? url($item) : \'\', json_decode($value, true)),');
                }
                if ($structure['form']) {
                    $modelFuncLike = $modelFuncLike->addBody('set: fn ($value) => json_encode(array_map(fn ($value) => remove_app_url($value), $value))');
                }

                $modelFuncLike->addBody(');');
            }
        }

        return $file;
    }

    /**
     * get model namespace
     */
    public function getModelNamespace(): string
    {
        return Str::of(CatchAdmin::getModuleModelNamespace($this->module))->trim('\\')->toString();
    }

    /**
     * get model name
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
     * @return array
     */
    protected function getTableColumns(): array
    {
        return SchemaFacade::getColumns($this->tableName);
    }

    /**
     * get field in list
     */
    protected function getInForm(): array
    {
        $form = [];
        foreach ($this->structures as $structure) {
            if ($structure['form']) {
                $form[] = $structure['field'];
            }
        }

        return $form;
    }

    /**
     * searchable
     */
    protected function getSearchable(): array
    {
        $searchable = [];

        foreach ($this->structures as $structure) {
            if ($structure['search'] && $structure['field'] && $structure['search_op']) {
                $searchable[$structure['field']] = $structure['search_op'];
            }
        }

        return $searchable;
    }

    /**
     * @return $this
     */
    public function setStructures(array $structures): static
    {
        $this->structures = $structures;

        return $this;
    }

    /**
     * @param  $namespace
     * @param  $modelClass
     * @return Literal|string
     */
    protected function addModelClass($namespace, $modelClass): Literal|string
    {
        if (class_exists($modelClass)) {
            $namespace->addUse($modelClass);

            return new Literal(class_basename($modelClass) . '::class');
        }

        return $modelClass;
    }

    /**
     * 将 MySQL 数据类型转换为 PHP 类型
     *
     * @param  string  $mysqlType
     * @return string
     */
    protected function parseMysqlTypeToPhpType(string $mysqlType): string
    {
        // 移除括号内的长度信息，如 varchar(255) -> varchar
        $baseType = preg_replace('/\(.*\)/', '', $mysqlType);

        // 整数类型
        if (preg_match('/(int|tinyint|smallint|mediumint|bigint)/', $baseType)) {
            return 'int';
        }

        // 浮点数类型
        if (preg_match('/(float|double|decimal|numeric|real)/', $baseType)) {
            return 'float';
        }

        // 字符串类型 (包含日期时间、字符串和二进制数据类型)
        if (preg_match('/(datetime|timestamp|date|time|char|varchar|text|tinytext|mediumtext|longtext|enum|set|blob|binary|varbinary|tinyblob|mediumblob|longblob)/', $baseType)) {
            return 'string';
        }

        // JSON 类型
        if ($baseType === 'json') {
            return 'array';  // 在 PHP 中通常表示为数组
        }

        // 默认返回 mixed 类型
        return 'mixed';
    }
}
