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
}