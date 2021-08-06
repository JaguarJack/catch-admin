<?php
namespace catcher\generate\factory;

use catcher\exceptions\FailedException;
use catcher\facade\FileSystem;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
use catcher\Utils;
use JaguarJack\Generate\Build\Class_;
use JaguarJack\Generate\Build\Property;
use JaguarJack\Generate\Build\Value;
use JaguarJack\Generate\Generator;
use JaguarJack\Generate\Types\Array_;
use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\ArrayItem;
use think\facade\Db;
use think\helper\Str;

class Model extends Factory
{
    /**
     * done
     *
     * @time 2020年11月19日
     * @param $params
     * @return string
     */
    public function done(array $params): string
    {
        $content = $this->getContent($params);

        $modelPath = $this->getGeneratePath($params['model']);

        FileSystem::put($modelPath, $content);

        if (!file_exists($modelPath)) {
            throw new FailedException('create model failed');
        }

        return $modelPath;
    }

    /**
     * get contents
     *
     * @time 2020年04月29日
     * @param $params
     * @return string|string[]
     * @throws \JaguarJack\Generate\Exceptions\TypeNotFoundException
     */
    public function getContent($params)
    {
        $extra = $params['extra'];

        $table = Utils::tableWithPrefix($params['table']);

        [$modelName, $namespace] = $this->parseFilename($params['model']);

        // 如果填写了表名并且没有填写模型名称 使用表名作为模型名称
        if (!$modelName && $table) {
            $modelName = ucfirst(Str::camel($table));
            $params['model'] .= $modelName;
        }

        if (!$modelName) {
            throw new FailedException('model name not set');
        }

        $softDelete = $extra['soft_delete'];

        return Generator::namespace($namespace)
                    ->class($modelName, function (Class_ $class, Generator $generator) use ($table){
                        $class->extend('Model');

                        if ($this->hasTableExists($table)) {
                            // 设置 class comment
                            $class->setDocComment($this->buildClassComment($table));

                            // 设置 name 属性
                            $generator->property('field', function (Property $property) use ($table){
                                return $property->setDefault($this->getFields($table));
                            });
                        }

                        $generator->property('name', function (Property $property) use ($table){
                            return $property->setDefault(Utils::tableWithoutPrefix($table));
                        });
                    })
                    ->uses([
                        $softDelete ? 'catcher\base\CatchModel as Model' : 'think\Model'
                    ])
                    ->when(! $softDelete, function (Generator $generator){
                        $generator->traits([
                            BaseOptionsTrait::class,
                            ScopeTrait::class,
                        ]);
                    })
                    ->print();
    }

    /**
     * 提供模型字段属性提示
     *
     * @time 2021年04月27日
     * @param $table
     * @return string
     */
    protected function buildClassComment($table): string
    {
       $fields = Db::name(Utils::tableWithoutPrefix($table))->getFieldsType();

       $comment = '/**' . PHP_EOL . ' *' . PHP_EOL;

       foreach ($fields as $field => $type) {
           $comment .= sprintf(' * @property %s $%s', $type, $field) . PHP_EOL;
       }

       $comment .= ' */';

       return $comment;
    }

    /**
     * get fields
     *
     * @time 2021年08月06日
     * @param $table
     * @return Array_
     */
    protected function getFields($table)
    {
        $columns = Db::getFields($table);

        $fetchItems = [];

        foreach ($columns as $column) {
            $item = new ArrayItem(Value::fetch($column['name']), null);

            $item->setDocComment(new Doc(sprintf('// %s', $column['comment'] ?? '' )));

            $fetchItems[] = $item;
        }

        return new Array_($fetchItems);
    }
}
