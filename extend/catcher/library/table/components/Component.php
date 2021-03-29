<?php
namespace catcher\library\table\components;

class Component
{
    /**
     * @var array
     */
    protected $attributes = [];

    protected $el;

    public function __construct()
    {
        $this->attributes['el'] = $this->el;
    }

    /**
     * 魔术方法
     *
     * @time 2021年03月21日
     * @param $method
     * @param $params
     * @return $this
     */
    public function __call($method, $params): Component
    {
        $this->attributes[$method] = $params[0];

        return $this;
    }

    /**
     * 输出
     *
     * @time 2021年03月23日
     * @return array
     */
    public function render()
    {
        return $this->attributes;
    }
}