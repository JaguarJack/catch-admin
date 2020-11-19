<?php
namespace catcher\generate\build;

use catcher\CatchAdmin;
use catcher\facade\FileSystem;
use catcher\generate\build\classes\Classes;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;

class CatchBuild
{
    protected $astBuilder;

    protected $outPath;

    protected $filename;

    public function __construct()
    {
        $this->astBuilder = app(BuilderFactory::class);
    }

    /**
     * 命名空间
     *
     * @time 2020年11月19日
     * @param string $namespace
     * @return $this
     */
    public function namespace(string $namespace)
    {
        $this->astBuilder = $this->astBuilder->namespace($namespace);

        return $this;
    }

    /**
     * use 方法体
     *
     * @time 2020年11月19日
     * @param $use
     * @return $this
     */
    public function use($use)
    {
        $this->astBuilder->addStmt($use);

        return $this;
    }


    /**
     * class 模版
     *
     * @time 2020年11月19日
     * @param Classes $class
     * @param \Closure $function
     * @return $this
     */
    public function class(Classes $class, \Closure $function)
    {
        $function($class);

        $this->astBuilder->addStmt($class->build());

        return $this;
    }

    /**
     * 条件
     *
     * @time 2020年11月19日
     * @param $condition
     * @param \Closure $closure
     * @return $this
     */
    public function when($condition, \Closure $closure)
    {
         if ($condition && $closure instanceof \Closure) {
             $closure($this);
         }

         return $this;
    }

    /**
     * 获取内容
     *
     * @time 2020年11月19日
     * @return string
     */
    public function getContent()
    {
        $stmts = array($this->astBuilder->getNode());

        $prettyPrinter = new Standard();

        return $prettyPrinter->prettyPrintFile($stmts);
    }

    /**
     * 输出
     *
     * @time 2020年11月19日
     * @return string
     */
    public function output()
    {
        return FileSystem::put($this->outPath . $this->filename, $this->getContent());
    }

    /**
     * 输出 Path
     *
     * @time 2020年11月19日
     * @param $path
     * @return $this
     */
    public function path($path)
    {
        CatchAdmin::makeDirectory($path);

        $this->outPath = $path;

        return $this;
    }

    /**
     * 设置文件名
     *
     * @time 2020年11月19日
     * @param $name
     * @return mixed
     */
    public function filename($name)
    {
        $this->filename = $name;

        return $this;
    }
}