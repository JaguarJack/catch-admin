<?php
namespace catcher\generate\factory;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use catcher\facade\FileSystem;
use catcher\generate\build\classes\Methods;
use catcher\generate\build\CatchBuild;
use catcher\generate\build\classes\Classes;
use catcher\generate\build\classes\Property;
use catcher\generate\build\classes\Uses;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ClosureUse;
use PhpParser\PrettyPrinter\Standard;
use think\helper\Str;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node;

class Controller extends Factory
{
    protected $methods = [];

    protected $uses = [
        'catcher\base\CatchRequest as Request',
        'catcher\CatchResponse',
        'catcher\base\CatchController'
    ];

    /**
     *
     * @time 2020年04月27日
     * @param $params
     * @return bool|string|string[]
     */
    public function done($params)
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

        if (!$className) {
            throw new FailedException('未填写控制器名称');
        }

        $use = new Uses();
        $class = new Classes($className);

        return (new CatchBuild())->namespace($namespace)
                                ->use($use->name('catcher\base\CatchRequest', 'Request'))
                                ->use($use->name('catcher\CatchResponse'))
                                ->use($use->name('catcher\base\CatchController'))
                                ->use($use->name($modelNamespace . '\\' . ucfirst($model), $asModel))
                                ->class($class->extend('CatchController')->docComment(), function (Classes $class) use ($asModel) {
                                    foreach ($this->getMethods($asModel) as $method) {
                                        $class->addMethod($method);
                                    }

                                    $class->addProperty(
                                        (new Property($asModel))->protected()
                                    );
                                })
                                ->getContent();
    }


    /**
     * 方法集合
     *
     * @time 2020年11月19日
     * @param $model
     * @return array
     */
    protected function getMethods($model)
    {
        $date = date('Y年m月d日 H:i');


        return [
            (new Methods('__construct'))
                ->public()
                ->param($model, ucfirst($model))
                ->docComment("\r\n")
                ->declare($model, $model),

            (new Methods('index'))->public()
                ->param('request', 'Request')
                ->docComment(
                    <<<TEXT

/**
 * 列表
 * @time $date
 * @param Request \$request 
 */
TEXT
                )
                ->returnType('\think\Response')->index($model),

            (new Methods('save'))
                ->public()
                ->param('request', 'Request')
                ->docComment(
                    <<<TEXT

/**
 * 保存信息
 * @time $date
 * @param Request \$request 
 */
TEXT
                )
                ->returnType('\think\Response')
                ->save($model),


            (new Methods('read'))->public()
                ->param('id')
                ->docComment(
                    <<<TEXT

/**
 * 读取
 * @time $date
 * @param \$id 
 */
TEXT

                )
                ->returnType('\think\Response')->read($model),


            (new Methods('update'))->public()
                ->param('request', 'Request')
                ->param('id')
                ->docComment(
                    <<<TEXT

/**
 * 更新
 * @time $date
 * @param Request \$request 
 * @param \$id
 */
TEXT
                )
                ->returnType('\think\Response')->update($model),


            (new Methods('delete'))->public()
                ->param('id')
                ->docComment(
                    <<<TEXT

/**
 * 删除
 * @time $date
 * @param \$id
 */
TEXT
                )
                ->returnType('\think\Response')->delete($model),

        ];
    }
}
