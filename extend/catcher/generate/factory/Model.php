<?php
namespace catcher\generate\factory;

use catcher\exceptions\FailedException;
use catcher\facade\FileSystem;
use catcher\generate\build\CatchBuild;
use catcher\generate\build\classes\Classes;
use catcher\generate\build\classes\Property;
use catcher\generate\build\classes\Traits;
use catcher\generate\build\classes\Uses;
use catcher\generate\build\types\Arr;
use catcher\traits\db\BaseOptionsTrait;
use catcher\traits\db\ScopeTrait;
use catcher\Utils;
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
    public function done($params)
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

        return (new CatchBuild)->namespace($namespace)
                        ->use((new Uses())->name('catcher\base\CatchModel', 'Model'))
                        ->when(!$softDelete, function (CatchBuild $build){
                            $build->use((new Uses())->name(BaseOptionsTrait::class));
                            $build->use((new Uses())->name(ScopeTrait::class));
                        })
                        ->class((new Classes($modelName))->extend('Model')->docComment(),
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
                        })->getContent();
    }
}