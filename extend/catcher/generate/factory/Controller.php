<?php
namespace catcher\generate\factory;

use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\facade\FileSystem;
use JaguarJack\Generate\Build\Class_;
use JaguarJack\Generate\Build\ClassMethod;
use JaguarJack\Generate\Build\Params;
use JaguarJack\Generate\Define;
use JaguarJack\Generate\Generator;
use think\helper\Str;
use think\Response;

class Controller extends Factory
{
    protected $methods = [];

    protected $uses = [
        'catcher\base\CatchRequest as Request',
        CatchResponse::class,
        CatchController::class,
    ];

    /**
     *
     * @time 2020年04月27日
     * @param array $params
     * @return bool|string|string[]
     */
    public function done(array $params)
    {
        // 写入成功之后
        $controllerPath = $this->getGeneratePath($params['controller']);

        if (FileSystem::put($controllerPath, $this->getContent($params))) {
            return $controllerPath;
        }

        throw new FailedException($params['controller'] . ' generate failed~');
    }

    /**
     * 获取内容
     *
     * @time 2020年04月28日
     * @param $params
     * @return bool|string|string[]
     */
    public function getContent($params)
    {
        if (!$params['controller']) {
            throw new FailedException('params has lost～');
        }

        // parse controller
        [$className, $namespace] = $this->parseFilename($params['controller']);

        [$model, $modelNamespace] = $this->parseFilename($params['model']);

        $asModel = lcfirst(Str::contains($model, 'Model') ? : $model . 'Model');

        if (! $className) {
            throw new FailedException('未填写控制器名称');
        }

        $this->uses[] = sprintf('%s as %s',  $modelNamespace . '\\' . ucfirst($model), ucfirst($asModel));
        $this->uses[] = Response::class;

        try {
            $content = Generator::namespace($namespace)
                ->class($className, function (Class_ $class, Generator $generator) use ($model, $asModel) {
                    $class->extend('CatchController');

                    $generator->property($asModel, function ($property) {
                        return $property->makeProtected();
                    });

                    // construct 方法
                    $generator->method('__construct', function (ClassMethod $method) use ($model, $asModel) {
                        return $method->addParam(
                            Params::name($asModel, ucfirst($asModel))
                        )->body([
                            Define::variable(Define::propertyDefineIdentifier($asModel), Define::variable($asModel))
                        ])->makePublic();
                    });

                    // index 方法
                    $generator->method('index', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method->body([
                            $generator->call('paginate', [
                                $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'getList'], [])
                            ], 'CatchResponse')->call()
                        ])->makePublic()->return()->setReturnType('Response');
                    });


                    // save 方法
                    $generator->method('save', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('request')->setType('Request')
                            ])
                            ->body([
                            $generator->call('success', [
                                $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'storeBy'], [
                                    $generator->methodCall(['request', 'post'], [])
                                ])
                            ], 'CatchResponse')->call()
                        ])->makePublic()->return('Response');
                    });

                    // read 方法
                    $generator->method('read', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('id'),
                            ])
                            ->body([
                                $generator->call('success', [
                                    $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'findBy'], [
                                        Define::variable('id'),
                                    ])
                                ], 'CatchResponse')->call()
                            ])->makePublic()->return('Response');
                    });


                    // update 方法
                    $generator->method('update', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('id'),
                                Params::name('request')->setType('Request')
                            ])
                            ->body([
                            $generator->call('success', [
                                $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'updateBy'], [
                                    Define::variable('id'),
                                    $generator->methodCall(['request', 'post'], [])
                                ])
                            ], 'CatchResponse')->call()
                        ])->makePublic()->return('Response');
                    });

                    // read 方法
                    $generator->method('delete', function (ClassMethod $method, Generator $generator) use ($asModel) {
                        return $method
                            ->addParam([
                                Params::name('id'),
                            ])
                            ->body([
                                $generator->call('success', [
                                    $generator->methodCall([Define::propertyDefineIdentifier($asModel), 'deleteBy'], [
                                        Define::variable('id'),
                                    ])
                                ], 'CatchResponse')->call()
                            ])->makePublic()->return('Response');
                    });

                })->uses($this->uses)->print();
        } catch (\Exception $e) {
           throw new FailedException($e->getMessage());
        }

        return $content;
    }
}
