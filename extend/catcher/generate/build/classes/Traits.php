<?php
namespace catcher\generate\build\classes;

use PhpParser\BuilderFactory;

class Traits
{
    protected $traitBuild;

    protected $build;

    public function use(...$names)
    {
        $this->build = new BuilderFactory;

        $this->traitBuild = call_user_func_array([$this->build, 'useTrait'], $names);

        return $this;
    }

    public function and($name)
    {
        $this->traitBuild->and($name);

        return $this;
    }

    /**
     * with
     *
     * @time 2020年11月19日
     * @param \Closure|null $closure
     * @return $this
     */
    public function with(\Closure $closure = null)
    {
        if ($closure instanceof \Closure) {
            $this->traitBuild->withe($closure($this));

            return $this;
        }

        return $this;
    }

    /**
     * @time 2020年11月19日
     * @param $name
     * @param null $method
     * @return $this
     */
    public function adaptation($name, $method = null)
    {
        $this->build = $this->build->traitUseAdaptation($name. $method);

        return $this;
    }

    /**
     * @time 2020年11月19日
     * @param $name
     * @return $this
     */
    public function as($name)
    {
        $this->build->as($name);

        return $this;
    }


    /**
     * @time 2020年11月19日
     * @param $name
     * @return $this
     */
    public function insteadof($name)
    {
        $this->build->insteadof($name);

        return $this;
    }

    public function build()
    {
        return $this->traitBuild;
    }
}
