<?php

declare(strict_types=1);

namespace catcher\library;

use think\exception\ClassNotFoundException;

class ParseClass
{
    protected $namespace;

    protected $module;

    protected $controller;

    /**
     * 获取父类方法
     *
     * @return array
     * @throws \ReflectionException
     */
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

    /**
     * 获取所有方法
     *
     * @return array
     * @throws \ReflectionException
     */
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
     * @throws \ReflectionException
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


    /**
     * 获取class
     *
     * @time 2020年09月06日
     * @throws \ReflectionException
     * @return \ReflectionClass
     */
    public function getClass()
    {
        $class = $this->namespace . $this->module . '\\controller\\'. ucfirst($this->controller);

        if (class_exists($class)) {
            return new \ReflectionClass($class);
        }

        throw new ClassNotFoundException($this->controller . ' not found');
    }

    /**
     * @param $method
     * @return bool
     */
    protected function isMagicMethod($method)
    {
       return strpos($method, '__') !== false;
    }

    /**
     *
     * @param $module
     * @return $this
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/6/6
     */
    public function setModule($module)
    {
        $psr4 = (new Composer())->psr4Autoload();

        foreach ($psr4 as $key => $_module) {
            if ($_module == $module) {
                $this->namespace = $key;
                break;
            }
        }

        return $this;
    }

    /**
     *
     * @param $module
     * @param $controller
     * @return $this
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/6/6
     */
    public function setRule($module, $controller)
    {
        $this->module = $module;

        $this->controller = $controller;

        return $this;
    }

}