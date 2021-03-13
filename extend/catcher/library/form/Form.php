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
    use FormOptions;
    use FormValidates;

    protected $primaryKeyField = 'id';

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
        return array_merge($this->fields(), [
            self::hidden($this->primaryKeyField, 0)
        ]);
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
        if ($this->primaryKeyField) {
            array_push($rules, self::hidden($this->primaryKeyField, 0));
        }

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
    protected static function uploadImageUrl(): string
    {
        return env('app.domain') . '/cms/upload/image';
    }

    /**
     * 上传文件地址
     *
     * @time 2021年03月10日
     * @return string
     */
    protected static function uploadImageFile(): string
    {
        return env('app.domain') . '/cms/upload/file';
    }

    /**
     * 上传图片地址
     *
     * @time 2021年03月03日
     * @return string
     */
    protected static function uploadFileUrl(): string
    {
        return env('app.domain') . '/cms/upload/file';
    }


    protected static function authorization(): array
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
    public static function image(string $title, string $value = ''): Upload
    {
        return self::uploadImage('image', $title, self::uploadImageUrl(), $value)
                    ->uploadName('image')
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
    public static function images(string $title, array $value = []): Upload
    {
        return self::uploadImages('image', $title, self::uploadImageUrl(), $value)
                    ->uploadName('image')
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
    public static function file(string $title, string $value = ''): Upload
    {
        return self::uploadFile('file', $title, self::uploadFileUrl(), $value)
                        ->uploadName('image')
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
    public static function files(string $title, array $value = []): Upload
    {
        return self::uploadFiles('file', $title, self::uploadFileUrl(), $value)
                    ->uploadName('file')
                    ->data(['none' => ''])
                    ->headers(self::authorization());
    }
}
