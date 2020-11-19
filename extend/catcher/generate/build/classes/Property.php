<?php
namespace catcher\generate\build\classes;

use PhpParser\BuilderFactory;

class Property
{
    protected $propertyBuild;

    public function __construct(string $name)
    {
        $this->propertyBuild = (new BuilderFactory())->property($name);
    }


    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function public()
    {
        $this->propertyBuild->makePublic();

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function protected()
    {
        $this->propertyBuild->makeProtected();

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function private()
    {
        $this->propertyBuild->makePrivate();

        return $this;
    }

    /**
     * 注释
     *
     * @time 2020年11月16日
     * @param $comment
     * @return $this
     */
    public function static($comment)
    {
        $this->propertyBuild->makeStatic();

        return $this;
    }


    /**
     * set default
     *
     * @time 2020年11月16日
     * @param $value
     * @return $this
     */
    public function default($value)
    {
        $this->propertyBuild->setDefault($value);

        return $this;
    }


    public function type($type)
    {
        $this->propertyBuild->setType($type);

        return $this;
    }

    public function docComment($comment)
    {
        $this->propertyBuild->setDocComment($comment);

        return $this;
    }

    public function build()
    {
        return $this->propertyBuild;
    }
}
