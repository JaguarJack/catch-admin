<?php
namespace catcher\library\table\components;

class Button extends Component
{
    protected $el = 'button';


    public function icon(string $icon): Button
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    public function text(string $text): Button
    {
        $this->attributes['label'] = $text;

        return $this;
    }

    public function style(string $style): Button
    {
        $this->attributes['class'] = $style;

        return $this;
    }

    public function click(string $click): Button
    {
        $this->attributes['click'] = $click;

        return $this;
    }

    /**
     * 支持路由跳转
     *
     * @time 2021年04月28日
     * @param string $route
     * @return $this
     */
    public function to(string $route): Button
    {
        $this->attributes['route'] = $route;

        return $this;
    }
}