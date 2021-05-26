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

namespace catcher\library\form;

use catcher\exceptions\FailedException;
use catcher\library\form\components\AreaTrait;
use catcher\library\form\components\DatePicker;
use catcher\library\form\components\Editor;
use FormBuilder\Exception\FormBuilderException;
use FormBuilder\Factory\Elm;
use FormBuilder\UI\Elm\Components\Upload;
use FormBuilder\UI\Elm\Traits\CascaderFactoryTrait;
use FormBuilder\UI\Elm\Traits\CheckBoxFactoryTrait;
use FormBuilder\UI\Elm\Traits\ColorPickerFactoryTrait;
use FormBuilder\UI\Elm\Traits\DatePickerFactoryTrait;
use FormBuilder\UI\Elm\Traits\FormStyleFactoryTrait;
use FormBuilder\UI\Elm\Traits\FrameFactoryTrait;
use FormBuilder\UI\Elm\Traits\GroupFactoryTrait;
use FormBuilder\UI\Elm\Traits\HiddenFactoryTrait;
use FormBuilder\UI\Elm\Traits\InputFactoryTrait;
use FormBuilder\UI\Elm\Traits\InputNumberFactoryTrait;
use FormBuilder\UI\Elm\Traits\RadioFactoryTrait;
use FormBuilder\UI\Elm\Traits\RateFactoryTrait;
use FormBuilder\UI\Elm\Traits\SelectFactoryTrait;
use FormBuilder\UI\Elm\Traits\SliderFactoryTrait;
use FormBuilder\UI\Elm\Traits\SwitchesFactoryTrait;
use FormBuilder\UI\Elm\Traits\TimePickerFactoryTrait;
use FormBuilder\UI\Elm\Traits\TreeFactoryTrait;
use FormBuilder\UI\Elm\Traits\UploadFactoryTrait;
use FormBuilder\UI\Elm\Traits\ValidateFactoryTrait;
use FormBuilder\UI\Elm\Validate;

abstract class Form
{
    use CascaderFactoryTrait;
    use CheckBoxFactoryTrait;
    use ColorPickerFactoryTrait;
    use DatePickerFactoryTrait;
    use FrameFactoryTrait;
    use HiddenFactoryTrait;
    use InputNumberFactoryTrait;
    use InputFactoryTrait;
    use RadioFactoryTrait;
    use RateFactoryTrait;
    use SliderFactoryTrait;
    use SelectFactoryTrait;
    use FormStyleFactoryTrait;
    use SwitchesFactoryTrait;
    use TimePickerFactoryTrait;
    use TreeFactoryTrait;
    use UploadFactoryTrait;
    use ValidateFactoryTrait;
    use GroupFactoryTrait;
    use FormValidates;
    use AreaTrait;

    protected $primaryKeyField = 'id';

    protected $primaryKeyValue = 0;

    protected $fieldsValue = [];

    /**
     * 必须实现的
     *
     * @time 2021年03月06日
     * @return array
     */
    abstract public function fields(): array;

    /**
     * 创建 Form
     *
     * @time 2021年03月06日
     * @return array
     */
    public function create(): array
    {
        $fields = $this->getFields();

        // 获取表单字段填充的值
        if (method_exists($this, 'getFieldsValue')) {
            $this->fieldsValue = $this->getFieldsValue();

            $fields = $this->setFieldsValue($fields);
        }

        return $this->rule($fields);
    }

    /**
     * 设置 fields values
     *
     * @time 2021年03月12日
     * @param $fields
     * @return mixed
     */
    protected function setFieldsValue($fields)
    {
        foreach ($fields as $field) {
            $name = $field->getName();

            if (isset($this->fieldsValue[$name])) {
                $field->value($this->fieldsValue[$name]);
            }
        }

        return $fields;
    }

    /**
     * 获取 form fields
     *
     * @time 2021年03月12日
     * @return array
     */
    protected function getFields(): array
    {
        if ($this->primaryKeyField) {
            return array_merge($this->fields(), [
                self::hidden($this->primaryKeyField, $this->primaryKeyValue)
            ]);
        }

        return $this->fields();
    }

    /**
     * form rule
     *
     * @time 2021年03月06日
     * @param array $rules
     * @return array
     */
    public function rule(array $rules): array
    {
        try{
            return Elm::createForm('', $rules)->formRule();
        } catch (FormBuilderException $e) {
            throw new FailedException('Form Created Failed: ' .$e->getMessage());
        }
    }


    /**
     * 上传图片地址
     *
     * @time 2021年03月03日
     * @return string
     */
    public static function uploadImageUrl(): string
    {
        return env('app.domain') . '/upload/image';
    }

    /**
     * 上传文件地址
     *
     * @time 2021年03月10日
     * @return string
     */
    public static function uploadFileUrl(): string
    {
        return env('app.domain') . '/upload/file';
    }


    /**
     * auth token
     *
     * @time 2021年04月11日
     * @return string[]
     */
    public static function authorization(): array
    {
        return [
            'authorization' => 'Bearer ' . request()->user()->remember_token,
        ];
    }

    /**
     * 上传图片
     *
     * @time 2021年03月03日
     * @param $title
     * @param string $value
     * @return mixed
     */
    public static function image(string $title, string $field = 'image', string $value = ''): Upload
    {
        return self::uploadImage($field, $title, self::uploadImageUrl(), $value)
                    ->uploadName($field)
                    ->data(['none' => ''])
                    ->headers(self::authorization());
    }

    /**
     * 多图
     *
     * @time 2021年03月03日
     * @param $title
     * @param array $value
     * @return mixed
     */
    public static function images(string $title, $field = 'images', array $value = []): Upload
    {
        return self::uploadImages($field, $title, self::uploadImageUrl(), $value)
                    ->uploadName($field)
                    ->data(['none' => ''])
                    ->headers(self::authorization());
    }

    /**
     * 上传文件
     *
     * @time 2021年03月10日
     * @param string $title
     * @param string $value
     * @return Upload
     */
    public static function file(string $title, $field = 'file', string $value = ''): Upload
    {
        return self::uploadFile($field, $title, self::uploadFileUrl(), $value)
                        ->uploadName($field)
                        ->data(['none' => ''])
                        ->headers(self::authorization());
    }

    /**
     * 上传多文件
     *
     * @time 2021年03月10日
     * @param string $title
     * @param array $value
     * @return Upload
     */
    public static function files(string $title, $field = 'files',array $value = []): Upload
    {
        return self::uploadFiles($field, $title, self::uploadFileUrl(), $value)
                    ->uploadName($field)
                    ->data(['none' => ''])
                    ->headers(self::authorization());
    }

    /**
     * options
     *
     * @time 2021年03月24日
     * @return FormOptions
     */
    public static function options(): FormOptions
    {
        return new FormOptions();
    }

    /**
     * props
     *
     * @time 2021年03月30日
     * @param $label
     * @param string $value
     * @param array $extra
     * @param array $data
     * @return array
     */
    public static function props($label, $value = 'id', array $extra = [], array $data = []): array
    {
        $props = [
            'props' => array_merge([
                'label' => $label,
                'value' => $value,
            ], $extra)
        ];

        if (count($data)) {
            $props['data'] = $data;
        }

        return $props;
    }

    /**
     * 不需要 props
     *
     * @time 2021年03月30日
     * @param $label
     * @param string $value
     * @param array $extra
     * @return array
     */
    public static function treeProps($label, $value = 'id', array $extra = []): array
    {
        return array_merge([
                'label' => $label,
                'value' => $value,
            ], $extra);
    }

    /**
     * col
     *
     * @time 2021年03月30日
     * @param int $col
     * @return array
     */
    public static function col(int $col): array
    {
        return ['span' => $col];
    }

    /**
     * 编辑器
     *
     * @time 2021年04月08日
     * @param $field
     * @param $title
     * @param string $value
     * @return Editor
     */
    public static function editor(string $field, string $title, string $value = ''): Editor
    {
        return new Editor($field, $title, $value);
    }


    /**
     * 日期组件
     *
     * @param string $field
     * @param string $title
     * @param string $value
     * @param string $type
     * @return DatePicker
     */
    public static function datePicker($field, $title, $value = '', $type = DatePicker::TYPE_DATE_TIME): DatePicker
    {
        return (new DatePicker($field, $title, $value))->type($type);
    }

    /**
     * 日期验证
     *
     * @time 2021年05月26日
     * @param string $trigger
     * @return Validate
     */
    public static function validateDate($trigger = Validate::TRIGGER_CHANGE): Validate
    {
        return new Validate('', $trigger);
    }
}
