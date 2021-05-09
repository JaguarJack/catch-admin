<?php
namespace catcher\library\table;

use catcher\base\CatchModel;
use FormBuilder\UI\Elm\Components\Input;
use FormBuilder\UI\Elm\Components\Select;
use FormBuilder\UI\Elm\Components\DatePicker;
use catcher\library\form\Form;

class Search
{
    protected static $label = '';

    public static function label(string $label): Search
    {
        self::$label = $label;

        return new self();
    }

    /**
     * 名称
     *
     * @time 2021年03月23日
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public static function name(string $placeholder): Input
    {
       return Form::input('name', self::$label)->placeholder($placeholder);
    }

    /**
     * 状态
     *
     * @time 2021年03月23日
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\Select
     */
    public static function status(string $placeholder = '请选择状态'): Select
    {
      return self::select('status', $placeholder, [
                          [ 'value' => CatchModel::ENABLE, 'label'=> ' 正常'],
                          [ 'value' => CatchModel::DISABLE, 'label'=> ' 禁用']
                      ]);
    }

    /**
     * 开始时间
     *
     * @time 2021年03月23日
     * @param string $placeholder
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public static function startAt(string $placeholder = '请选择开始时间'): DatePicker
    {
        return self::label(self::$label ? : '开始时间')->datetime('start_at', $placeholder);
    }

    /**
     * 结束时间
     *
     * @time 2021年03月23日
     * @param string $placeholder
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public static function endAt(string $placeholder = '请选择结束时间'): DatePicker
    {
        return self::label(self::$label ? : '结束时间')->datetime('end_at', $placeholder);
    }

    /**
     * 文本
     *
     * @time 2021年03月23日
     * @param $name
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public static function text(string $name, string $placeholder): Input
    {
        return Form::input($name, self::$label)->placeholder($placeholder);
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
    public static function select(string $name, string $placeholder, array $options): Select
    {
        return Form::select($name, self::$label)->placeholder($placeholder)->options($options);
    }

    /**
     * 选择时间
     *
     * @time 2021年03月23日
     * @param $name
     * @param $placeholder
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public static function datetime(string $name, string $placeholder): DatePicker
    {
        return Form::dateTime($name, self::$label)
            ->placeholder($placeholder)
            ->format('yyyy-MM-dd HH:mm:ss')
            ->valueFormat('yyyy-MM-dd HH:mm:ss');
    }

    /**
     * 代理方法
     *
     * @time 2021年04月06日
     * @param $method
     * @param $params
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        return Form::{$method}(...$params);
    }

    /**
     * 代理方法
     *
     * @time 2021年04月06日
     * @param $method
     * @param $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        return Form::{$method}(...$params);
    }
}