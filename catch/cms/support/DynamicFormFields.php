<?php
// +----------------------------------------------------------------------
// | Catch-CMS Design On 2020
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\cms\support;

use catchAdmin\cms\model\Models;
use catcher\library\form\Form;
use catchAdmin\cms\model\ModelFields;

class DynamicFormFields
{
    protected $defaultRules = [
        'alpha'       => ['^[A-Za-z]+$', '必须为纯字母'],
        'alphaNum'    => ['^[A-Za-z0-9]+$', '必须为字母和数字'],
        'alphaDash'   => ['^[A-Za-z0-9\-\_]+$', '必须为字母和数字，下划线_及破折号-'],
        'mobile'      => ['^1[3-9]\d{9}$','请输入正确的手机号格式'],
        'idCard'      => ['(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)','身份证输入格式不正确'],
        'zip'         => ['\d{6}','请输入有效的邮政编码'],
        'ip'          => ['((?:(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d)\\.){3}(?:25[0-5]|2[0-4]\\d|[01]?\\d?\\d))', '请输入正确的 IP 地址'],
        'password'    => ['^[a-zA-Z]\w{5,17}$', '以字母开头，长度在6~18之间，只能包含字母、数字和下划线'],
        'strong_password' => ['^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,10}$', '必须包含大小写字母和数字的组合，不能使用特殊字符，长度在8-10之间'],
        'landLine'    => ['\d{3}-\d{8}|\d{4}-\d{7}', '请输入正确的座机格式'],
        'chinese_character' => ['^[\u4e00-\u9fa5]{0,}$', '必须为纯汉字']
    ];

    /**
     * build form field
     *
     * @time 2021年03月10日
     * @param $modelId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return array
     */
    public function build($tableName): array
    {
        $fields = [];

        ModelFields::whereIn('model_id', Models::where('table_name', $tableName)->column('id'))
                ->where('status', ModelFields::ENABLE)
                ->select()
                ->each(function ($field) use (&$fields){
                    $formField = $this->{$field['type']}($field);

                    $formField = $this->getOptions($formField, $field['options'] ?? '');

                    $formField = $this->appendValidates($formField, $field['rules']);

                    $formField = $this->pattern($formField, $field['pattern']);

                    $fields[] = $formField;
                });

        return $fields;
    }

    /**
     * 字符串
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public function string($field): \FormBuilder\UI\Elm\Components\Input
    {
        return Form::input($field['name'], $field['title']);
    }

    /**
     * 整型
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\InputNumber
     */
    public function int($field): \FormBuilder\UI\Elm\Components\InputNumber
    {
        return Form::number($field['name'], $field['title']);
    }

    /**
     * 浮点
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\InputNumber
     */
    public function float($field): \FormBuilder\UI\Elm\Components\InputNumber
    {
        return Form::number($field['name'], $field['tittle']);
    }

    /**
     * textarea
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public function textarea($field): \FormBuilder\UI\Elm\Components\Input
    {
        return Form::textarea($field['name'], $field['title']);
    }

    /**
     * 编辑器
     *
     * @time 2021年03月09日
     * @param $field
     * @return mixed
     */
    public function text($field)
    {
        return Form::editor($field['name'], $field['title'])->language();
    }

    /**
     * longtext
     *
     * @time 2021年03月09日
     * @param $field
     * @return mixed
     */
    public function longtext($field)
    {
        return Form::editor($field['name'], $field['title']);
    }

    /**
     * 日期类型
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public function date($field): \FormBuilder\UI\Elm\Components\DatePicker
    {
        return Form::date($field['name'], $field['title']);
    }

    /**
     * 日期时间
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\DatePicker
     */
    public function datetime($field): \FormBuilder\UI\Elm\Components\DatePicker
    {
        return Form::dateTime($field['name'], $field['title']);
    }

    /**
     * 图片
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Upload
     */
    public function image($field): \FormBuilder\UI\Elm\Components\Upload
    {
        return Form::image($field['title'], $field['name']);
    }

    /**
     * 多图
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Upload
     */
    public function images($field): \FormBuilder\UI\Elm\Components\Upload
    {
        return Form::images($field['title'], $field['name']);
    }

    /**
     * 上传文件
     *
     * @time 2021年03月09日
     * @param $field
     * @return mixed
     */
    public function file($field)
    {
        return Form::file($field['title'], $field['name']);
    }

    /**
     * 上传多个文件
     *
     * @time 2021年03月09日
     * @param $field
     * @return mixed
     */
    public function files($field)
    {
        return Form::files($field['title'], $field['name']);
    }

    /**
     * 下拉框
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Select
     */
    public function select($field): \FormBuilder\UI\Elm\Components\Select
    {
        return Form::select($field['name'], $field['title']);
    }

    /**
     * checkbox
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Select
     */
    public function checkbox($field): \FormBuilder\UI\Elm\Components\Select
    {
        return Form::select($field['name'], $field['title']);
    }

    /**
     * radio
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Select
     */
    public function radio($field): \FormBuilder\UI\Elm\Components\Select
    {
        return Form::select($field['name'], $field['title'])->options($this->getOptions($field['options']));
    }

    /**
     * 密码
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Input
     */
    public function password($field): \FormBuilder\UI\Elm\Components\Input
    {
        return Form::password($field['name'], $field['title']);
    }

    /**
     * 颜色
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\ColorPicker
     */
    public function color($field): \FormBuilder\UI\Elm\Components\ColorPicker
    {
        return Form::color($field['name'], $field['title']);
    }

    /**
     * 省市选择
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Cascader
     */
    public function city($field): \FormBuilder\UI\Elm\Components\Cascader
    {
        return Form::city($field['name'], $field['title']);
    }

    /**
     * 省市区选择
     *
     * @time 2021年03月09日
     * @param $field
     * @return \FormBuilder\UI\Elm\Components\Cascader
     */
    public function area($field): \FormBuilder\UI\Elm\Components\Cascader
    {
        return Form::cityArea($field['name'], $field['title']);
    }

    /**
     * options
     *
     * @time 2021年03月09日
     * @param $formField
     * @param $options
     * @return mixed
     */
    protected function getOptions($formField, $options)
    {
        if (!$options) {
            return $formField;
        }

        return $formField->options(Helper::getOptions($options));
    }

    /**
     * 验证规则
     *
     * @time 2021年03月09日
     * @param $formField
     * @param $validates
     * @return mixed
     */
    protected function appendValidates($formField, $validates)
    {
        if (count($validates)) {
            foreach ($validates as $validate) {
                if ($validate === 'require') {
                    $formField = $formField->required();
                }

                switch ($validate) {
                    case 'number':
                        $formField->appendValidate(Form::validateNum()->message('请输入数字'));
                        break;
                    case 'integer':
                        $formField->appendValidate(Form::validateInt()->message('请输入整型数字'));
                        break;
                    case 'float':
                        $formField->appendValidate(Form::validateFloat()->message('请输入浮点型数字'));
                        break;
                    case in_array($validate, ['email', 'url', 'date']):
                        $message = [
                            'email' => '邮箱格式不正确',
                            'url'   => 'url 地址格式不正确',
                            'date' => '日期格式不正确'
                        ];
                        $method = 'validate' . ucfirst($validate);
                        $formField->appendValidate(Form::{$method}()->message($message[$validate]));
                        break;
                    default:
                        if (isset($this->defaultRules[$validate])) {
                            list($pattern, $message) = $this->defaultRules[$validate];

                            $formField->appendValidate(
                                Form::validateStr()->pattern($pattern)->message($message)
                            );
                        }
                        break;
                }
            }
        }

        return $formField;
    }

    /**
     * 正则
     *
     * @time 2021年03月10日
     * @param $formField
     * @param $pattern
     * @return mixed
     */
    protected function pattern($formField, $pattern)
    {
        if ($pattern) {
            list($pattern, $message) = explode('|', $pattern);

            return $formField->appendValidate(
                Form::validateStr()->pattern($pattern)->message($message)
            );
        }

        return $formField;
    }
}