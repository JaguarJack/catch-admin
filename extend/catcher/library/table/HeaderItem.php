<?php
namespace catcher\library\table;

class HeaderItem
{
    use ComponentsTrait;

    protected $attributes = [];

    public function __construct(string $label)
    {
        $this->attributes['label'] = $label;

        return $this;
    }

    public static function label(string $label): HeaderItem
    {
        return new self($label);
    }

    public function prop(string $prop): HeaderItem
    {
        $this->attributes['prop'] = $prop;

        return $this;
    }

    public function width(string $width): HeaderItem
    {
        $this->attributes['width'] = $width;

        return $this;
    }

    public function actions(array $actions): HeaderItem
    {
        foreach ($actions as $action) {
            $this->attributes['action'][] = $action->render();
        }

        return $this;
    }

    public function isBubble($bubble = false): HeaderItem
    {
        $this->attributes['isBubble'] = $bubble;

        return $this;
    }

    /**
     * selection
     *
     * @time 2021年03月29日
     * @return mixed
     */
    public function selection()
    {
        return $this->width(50)->type('selection');
    }


    /**
     * 动态访问
     *
     * @time 2021年03月24日
     * @param $method
     * @param $params
     * @return $this
     */
    public function __call($method, $params): HeaderItem
    {
        $this->attributes[$method] = $params[0];

        return $this;
    }

    public function __get($key)
    {
        return $this->{$key};
    }
}