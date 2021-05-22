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

namespace catchAdmin\cms\tables\forms;
use catcher\library\form\Form;

class Field extends Form
{
    /**
     * create form
     *
     * @time 2021年03月02日
     * @return array
     */
    public function fields(): array
    {
        // TODO: Implement create() method.
        return [
            // 字段名称
            Form::input('name', '字段名称')->required()
                ->appendValidate(
                    Form::validatePattern('^[a-z]{1,30}_?[a-z]{1,30}?')->message('字段名称只支持小写字母，_组合')
                )
                ->placeholder('由英文和下划线组成')
                ->clearable(true),
            // 字段释义
            Form::input('title', 'label名称')->required()->placeholder('表单Label')->clearable(true),
            // 字段类型
            Form::select('type', '类型')
                ->required()
                ->options($this->types())
                ->style(['width' => '100%'])
                ->appendControl('string', [Form::number('length', '长度', 1)->max(1000)->min(1)])
                ->appendControl('int', [Form::number('length', '长度', 1)->max(11)->min(1)])
                ->appendControl('select', [Form::textarea('options', '选项')->placeholder('格式(value|label)，一个选项占用一行')])
                ->appendControl('radio', [Form::textarea('options', '选项')->placeholder('格式(value|label)，一个选项占用一行')])
                ->appendControl('checkbox', [Form::textarea('options', '选项')->placeholder('格式(value|label)，一个选项占用一行')])
                ->clearable(true),
            // 默认值
            Form::input('default_value', '默认值'),
            // 加索引
            Form::radio('is_index', '加索引', 2)->options(
                self::options()->add('是', 1)
                    ->add('否', 2)->render()
            ),
            // 值唯一
            Form::radio('is_unique', '值唯一', 2)->options(
                self::options()->add('是', 1)
                    ->add('否', 2)->render()
            ),
            // 表单验证规则
            Form::selectMultiple('rules', '验证规则')
                    ->options($this->rules()),
            // 正则验证
            Form::input('pattern', '正则验证')->placeholder('格式如下(正则|提示信息)'),
            // 排序
            Form::number('sort', '排序', 1)->max(10000)->min(1),
            // 字段是否在表单中显示
            Form::radio('status', '状态', 1)->options(
                self::options()->add('正常', 1)
                    ->add('隐藏', 2)->render()
            )
        ];
    }

    /**
     * 表单类型
     *
     * @time 2021年03月06日
     * @return \string[][]
     */
    protected function types(): array
    {
        return [
            ['value' => 'string', 'label' => '字符串'],
            ['value' => 'int', 'label' => '整数'],
            ['value' => 'float', 'label' => '小数'],
            ['value' => 'textarea', 'label' => 'textarea文本'],
            ['value' => 'text', 'label' => '编辑器(建议)'],
            ['value' => 'longtext', 'label' => '编辑器(支持超大文本)'],
            ['value' => 'date', 'label' => '日期型'],
            ['value' => 'datetime', 'label' => '日期时间型'],
            ['value' => 'image', 'label' => '图片上传'],
            ['value' => 'images', 'label' => '多图上传'],
            ['value' => 'file', 'label' => '文件上传'],
            ['value' => 'files', 'label' => '多文件上传'],
            ['value' => 'select', 'label' => '列表'],
            ['value' => 'checkbox', 'label' => '复选框'],
            ['value' => 'password', 'label' => '密码框'],
            ['value' => 'color', 'label' => '颜色选项'],
            ['value' => 'radio', 'label' => '单选'],
        ];
    }


    protected function rules(): array
    {
        return [
            ['value' => 'require', 'label' => '必填'],
            ['value' => 'number', 'label' => '数字'],
            ['value' => 'integer', 'label' => '整数'],
            ['value' => 'float', 'label' => '浮点数'],
            ['value' => 'email', 'label' => '邮箱'],
            ['value' => 'date', 'label' => '日期'],
            ['value' => 'alpha', 'label' => '纯字母'],
            ['value' => 'alphaNum', 'label' => '纯字母和数字'],
            ['value' => 'url', 'label' => 'url地址'],
            ['value' => 'ip', 'label' => 'ip地址'],
            ['value' => 'mobile', 'label' => '手机号'],
            ['value' => 'idCard', 'label' => '身份证'],
            ['value' => 'zip', 'label' => '邮政编码'],
            ['value' => 'password', 'label' => '密码'],
            ['value' => 'strong_password', 'label' => '强密码'],
            ['value' => 'landLine', 'label' => '座机'],
            ['value' => 'chinese_character', 'label' => '汉字']
        ];
    }
}