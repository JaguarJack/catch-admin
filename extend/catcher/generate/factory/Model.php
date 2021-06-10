<?php
namespace catcher\generate\factory;

use catcher\exceptions\FailedException;
use catcher\facade\FileSystem;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
use catcher\Utils;
use JaguarJack\Generate\Build\Class_;
use JaguarJack\Generate\Build\Property;
use JaguarJack\Generate\Generator;
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
            $params['model'] = $params['model'] . $modelName;
        }

        if (!$modelName) {
            throw new FailedException('model name not set');
        }

        $softDelete = $extra['soft_delete'];

        return Generator::namespace($namespace)
                    ->class($modelName, function (Class_ $class, Generator $generator) use ($table){
                        $class->extend('Model');

                        $class->setDocComment($this->buildClassComment($table));

                        $generator->property('name', function (Property $property) use ($table){
                            return $property->setDefault(Utils::tableWithoutPrefix($table));
                        });

                        if ($this->hasTableExists($table)) {
                            $generator->property('field', function (Property $property) use ($table){
                                return $property->setDefault(array_column(Db::getFields($table), 'name'));
                            });
                        }
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
/**
        return (new CatchBuild)->namespace($namespace)
                        ->use((new Uses())->name('catcher\base\CatchModel', 'Model'))
                        ->when(!$softDelete, function (CatchBuild $build){
                            $build->use((new Uses())->name(BaseOptionsTrait::class));
                            $build->use((new Uses())->name(ScopeTrait::class));
                        })
                        ->class((new Classes($modelName))
                            ->extend('Model')
                            ->docComment($this->buildClassComment($table)),
                            function (Classes $class) use ($softDelete, $table) {
                            if (!$softDelete) {
                                $class->addTrait(
                                    (new Traits())->use('BaseOptionsTrait', 'ScopeTrait')
                                );
                            }

                            $class->addProperty(
                                (new Property('name'))->default(
                                    Utils::tableWithoutPrefix($table)
                                )->docComment('// 表名')
                            );

                            $class->when($this->hasTableExists($table), function ($class) use ($table){
                                $class->addProperty(
                                    (new Property('field'))->default(
                                        (new Arr)->build(Db::getFields($table))
                                    )->docComment('// 数据库字段映射'));
                            });
                        })->getContent();*/
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
}
