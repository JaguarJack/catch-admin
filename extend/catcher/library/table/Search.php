<?php
namespace catcher\library\table;


use catcher\library\form\Form;

class Search
{
    /**
     * 名称
     *
     * @time 2021年03月23日
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public static function name($placeholder): \FormBuilder\UI\Elm\Components\Input
    {
       return Form::input('name', '')->placeholder($placeholder);
    }

    /**
     * 状态
     *
     * @time 2021年03月23日
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\Select
     */
    public static function status($placeholder = '请选择状态'): \FormBuilder\UI\Elm\Components\Select
    {
      return self::select('status', $placeholder, [
                          [ 'value' => 1, 'label'=> ' 正常'],
                          [ 'value' => 2, 'label'=> ' 禁用']
                      ]);
    }

    /**
     * 开始时间
     *
     * @time 2021年03月23日
     * @param string $placeholder
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public static function startAt($placeholder = '开始时间'): \FormBuilder\UI\Elm\Components\DatePicker
    {
        return self::datetime('start_at', '')
            ->placeholder($placeholder)
            ->format('yy-MM-DD HH:i:s');
    }

    /**
     * 结束时间
     *
     * @time 2021年03月23日
     * @param string $placeholder
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public static function endAt($placeholder = '结束时间'): \FormBuilder\UI\Elm\Components\DatePicker
    {
        return self::datetime('end_at', '')
            ->placeholder($placeholder)
            ->format('yy-MM-DD HH:i:s');
    }

    /**
     * 文本
     *
     * @time 2021年03月23日
     * @param $name
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public static function text($name, $placeholder): \FormBuilder\UI\Elm\Components\Input
    {
        return Form::input($name, '')->placeholder($placeholder);
    }

    /**
     * 选择
     *
     * @time 2021年03月23日
     * @param $name
     * @param $placeholder
     * @param $options
     * @return \FormBuilder\UI\Elm\Components\Select
     */
    public static function select($name, $placeholder, $options): \FormBuilder\UI\Elm\Components\Select
    {
        return Form::select($name, '')->placeholder($placeholder)
            ->options($options);
    }

    /**
     * 选择时间
     *
     * @time 2021年03月23日
     * @param $name
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public static function datetime($name, $placeholder): \FormBuilder\UI\Elm\Components\DatePicker
    {
        return Form::dateTime($name, '')
            ->placeholder($placeholder)
            ->format('yyyy-MM-dd HH:i:s');
    }
}