<?php
namespace catcher\generate\build\classes;

use PhpParser\BuilderFactory;

class Classes
{
    protected $classBuild;

    public function __construct(string $name)
    {
        $this->classBuild = (new BuilderFactory())->class($name);
    }

    /**
     * 设置 comment
     *
     * @time 2020年11月19日
     * @param string $comment
     * @return $this
     */
    public function docComment($comment="\r\n")
    {
        $this->classBuild->setDocComment($comment);

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @param $extend
     * @return $this
     */
    public function extend($extend)
    {
        $this->classBuild->extend($extend);

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @param $interfaces
     * @return $this
     */
    public function implement($interfaces)
    {
        $this->classBuild->implement($interfaces);

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function abstract()
    {
        $this->classBuild->makeAbstract();

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function final()
    {
        $this->classBuild->makeFinal();

        return $this;
    }

    public function build()
    {
        return $this->classBuild;
    }

    public function addMethod(Methods $method)
    {
        $this->classBuild->addStmt($method->build());

        return $this;
    }

    public function addProperty(Property $property)
    {
        $this->classBuild->addStmt($property->build());

        return $this;
    }

    public function addTrait(Traits $trait)
    {
        $this->classBuild->addStmt($trait->build());

        return $this;
    }

    /**
     * when
     *
     * @time 2020年11月19日
     * @param $condition
     * @param \Closure $closure
     * @return $this
     */
    public function when($condition, \Closure $closure)
    {
        if ($condition) {
            $closure($this);
        }

        return $this;
    }
}
