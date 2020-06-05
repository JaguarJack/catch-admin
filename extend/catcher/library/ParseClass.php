<?php
namespace catcher\library;

use think\helper\Str;

class ParseClass
{
    protected $namespace;

    protected $module;

    protected $controller;

    public function parentMethods()
    {
        $class = $this->getClass();

        $parent = $class->getParentClass();

        $methods = [];

        foreach ($parent->getMethods() as $method) {
            if (!$this->isMagicMethod($method->getName())) {
                $methods[] = $method->getName();
            }
        }

        return $methods;
    }


    public function methods()
    {
        $class = $this->getClass();

        $methods = [];

        foreach ($class->getMethods() as $method) {
            if (!$this->isMagicMethod($method->getName())) {
                $methods[] = $method->getName();
            }
        }

        return $methods;
    }


    /**
     * @return mixed
     */
    public function onlySelfMethods()
    {
        $methods = [];

        $parentMethods = $this->parentMethods();

        foreach ($this->methods() as $method) {
            if (!in_array($method, $parentMethods)) {
                $methods[] = $method;
            }
        }

       return  $methods;
    }


    public function getClass()
    {

        return new \ReflectionClass($this->namespace . $this->module . '\\controller\\'.

          ucfirst($this->controller));
    }


    protected function isMagicMethod($method)
    {
       return strpos($method, '__') !== false;
    }

    public function setModule($module)
    {
        $composer = \json_decode(file_get_contents(root_path() . 'composer.json'), true);

        $psr4 = $composer['autoload']['psr-4'];

        foreach ($psr4 as $key => $_module) {
            if ($_module == $module) {
                $this->namespace = $key;
                break;
            }
        }

        return $this;
    }


    public function setRule($module, $controller)
    {
        $this->module = $module;

        $this->controller = $controller;

        return $this;
    }

}