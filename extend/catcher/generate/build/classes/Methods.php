<?php
namespace catcher\generate\build\classes;

use catcher\generate\build\traits\CatchMethodReturn;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Expression;

class Methods
{

    use CatchMethodReturn;

    protected $methodBuild;

    public function __construct(string $name)
    {
        $this->methodBuild = (new BuilderFactory())->method($name);
    }

    public function public()
    {
        $this->methodBuild->makePublic();

        return $this;
    }

    public function protected()
    {
        $this->methodBuild->makeProtected();

        return $this;
    }

    public function private()
    {
        $this->methodBuild->makePrivate();

        return $this;
    }

    /**
     * set params
     *
     * @time 2020年11月16日
     * @param $type
     * @param $param
     * @param $default
     * @return $this
     */
    public function param($param, $type = null, $default = null)
    {
        $param  = (new BuilderFactory())->param($param);

        if ($type) {
            $param = $param->setType($type);
        }

        if ($default) {
            $param = $param->setDefault($default);
        }

        $this->methodBuild->addParam(
           $param
        );

        return $this;
    }

    /**
     * 定义
     *
     * @time 2020年11月18日
     * @param $variable
     * @param $value
     * @return $this
     */
    public function declare($variable, $value)
    {
        $smt = new Expression(
            new Assign(
                new PropertyFetch(
                    new Variable('this'),
                    new Identifier($variable)
                ),
                new Variable($value)
            )
        );

        $this->methodBuild->addStmt($smt);

        return $this;
    }

    /**
     * 返回值
     *
     * @time 2020年11月16日
     * @param $returnType
     * @return $this
     */
    public function returnType($returnType)
    {
        $this->methodBuild->setReturnType($returnType);

        return $this;
    }


    /**
     * 注释
     *
     * @time 2020年11月16日
     * @param $comment
     * @return $this
     */
    public function docComment(string $comment)
    {
        $this->methodBuild->setDocComment($comment);

        return $this;
    }

    /**
     * 抽象
     *
     * @time 2020年11月17日
     * @return $this
     */
    public function toAbstract()
    {
        $this->methodBuild->makeAbstract();

        return $this;
    }

    /**
     * final
     *
     * @time 2020年11月17日
     * @return $this
     */
    public function toFinal()
    {
        $this->methodBuild->makeFinal();

        return $this;
    }


    public function build()
    {
        return $this->methodBuild;
    }
}
