<?php
namespace catcher\generate\factory;

use catcher\exceptions\FailedException;
use catcher\generate\template\Controller as Template;
use think\helper\Str;

class Controller extends Factory
{
    protected $methods = [];

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
        if (file_put_contents($controllerPath, $this->getContent($params))) {
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

        $template = new Template();
        // parse controller
        [$className, $namespace] = $this->parseFilename($params['controller']);
        if (!$className) {
            throw new FailedException('未填写控制器名称');
        }
        
        // parse model
        [$model, $modelNamespace] = $this->parseFilename($params['model']);

        $use = implode(';',[
                'use ' . $params['model'] .' as '. $model . 'Model',
            ]) . ';';

        $content =  $template->header() .
            $template->nameSpace($namespace) .
            str_replace('{USE}', $model ? $use : '', $template->uses())  .
            $template->createClass($className);

        return str_replace('{CONTENT}', ($model ? $template->construct($model.'Model') : '') . rtrim($this->content($params, $template), "\r\n"), $content);
    }

    /**
     * parse use
     *
     * @time 2020年04月28日
     * @param $params
     * @return string
     */
    protected function parseUse($params)
    {

    }
    /**
     * content
     *
     * @time 2020年04月27日
     * @param $params
     * @param $template
     * @return string
     */
    protected function content($params, $template)
    {
        $content = '';

        if ($params['restful']) {
            $methods = $this->restful();
            $this->methods = array_merge($this->methods, $methods);
            foreach ($methods as $method) {
                $content .= $template->{$method[0]}();
            }
        }

        /**
        if (!empty($params['other_function'])) {
            $others = $this->parseOtherMethods($params['other_function']);
            $this->methods = array_merge($this->methods, $others);
            foreach ($others as $other) {
                $content .= $template->otherFunction($other[0], $other[1]);
            }
        }*/

        return $content;
    }

    /**
     * parse $method
     * class_method/http_method
     * @time 2020年04月27日
     * @param $methods
     * @return false|string[]
     */
    public function parseOtherMethods($methods)
    {
        $_methods = [];

        foreach ($methods as $method) {
            if (Str::contains($method, '/')) {
                $_methods[] = explode('/', $method);
            } else {
                // 默认使用 Get 方式
                $_methods[] = [$method, 'get'];
            }
        }

        return $_methods;
    }

    /**
     * restful 路由
     *
     * @time 2020年04月27日
     * @return \string[][]
     */
    public function restful()
    {
        return [
            ['index', 'get'],
            ['save', 'post'],
            ['read', 'get'],
            ['update', 'put'],
            ['delete', 'delete'],
        ];
    }

}
