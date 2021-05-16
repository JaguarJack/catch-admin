<?php
namespace catcher\library\table;

class HeaderItem
{
    use ComponentsTrait;

    protected $attributes = [];

    /**
     * HeaderItem constructor.
     * @param string $label
     */
    public function __construct(string $label)
    {
        $this->attributes['label'] = $label;

        $this->attributes['show'] = true;

        return $this;
    }

    /**
     * label
     *
     * @time 2021年05月15日
     * @param string $label
     * @return HeaderItem
     */
    public static function label(string $label = ''): HeaderItem
    {
        return new self($label);
    }

    /**
     * prop
     *
     * @time 2021年05月15日
     * @param string $prop
     * @return $this
     */
    public function prop(string $prop): HeaderItem
    {
        $this->attributes['prop'] = $prop;

        return $this;
    }

    /**
     * 宽度
     *
     * @time 2021年05月15日
     * @param string $width
     * @return $this
     */
    public function width(string $width): HeaderItem
    {
        $this->attributes['width'] = $width;

        return $this;
    }

    /**
     * align 宽度
     *
     * @time 2021年05月15日
     * @param string $align
     * @return $this
     */
    public function align(string $align): HeaderItem
    {
        $this->attributes['align'] = $align;

        return $this;
    }

    /**
     * 居中
     *
     * @time 2021年05月15日
     * @return $this
     */
    public function alignCenter(): HeaderItem
    {
        $this->attributes['align'] = 'center';

        return $this;
    }

    /**
     * 居右
     *
     * @time 2021年05月15日
     * @return $this
     */
    public function alignRight(): HeaderItem
    {
        $this->attributes['align'] = 'right';

        return $this;
    }

    /**
     * 操作
     *
     * @time 2021年05月15日
     * @param array $actions
     * @return $this
     */
    public function actions(array $actions): HeaderItem
    {
        foreach ($actions as $action) {
            $this->attributes['action'][] = $action->render();
        }

        return $this;
    }

    /**
     * 冒泡点击
     *
     * @time 2021年05月15日
     * @param false $bubble
     * @return $this
     */
    public function isBubble($bubble = false): HeaderItem
    {
        $this->attributes['isBubble'] = $bubble;

        return $this;
    }

    /**
     * 可排序
     *
     * @time 2021年03月31日
     * @return $this
     */
    public function sortable(): HeaderItem
    {
        $this->attributes['sortable'] = true;

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
     * 展开行
     *
     * @time 2021年05月07日
     * @return mixed
     */
    public function expand()
    {
        return $this->type('expand');
    }

    /**
     * 固定列
     *
     * @time 2021年05月07日
     * @param bool|string $fixed
     * @return bool|mixed
     */
    public function fixed($fixed = true)
    {
        return $this->attributes['fixed'] = $fixed;
    }

    /**
     * dont export
     *
     * @time 2021年04月22日
     * @return $this
     */
    public function dontExport(): HeaderItem
    {
        $this->attributes['export'] = false;

        return $this;
    }

    /**
     * dont import
     *
     * @time 2021年04月22日
     * @return $this
     */
    public function dontImport(): HeaderItem
    {
        $this->attributes['import'] = false;

        return $this;
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
