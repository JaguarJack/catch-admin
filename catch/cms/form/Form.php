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

namespace catchAdmin\cms\form;

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
        return $this->rule($this->fields());
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
        try {
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
    protected function uploadImageUrl(): string
    {
        return env('app.domain') . '/cms/upload/image';
    }


    /**
     * 上传图片地址
     *
     * @time 2021年03月03日
     * @return string
     */
    protected function uploadFileUrl(): string
    {
        return env('app.domain') . '/cms/upload/file';
    }


    protected function authorization(): array
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
     * @return \FormBuilder\UI\Elm\Components\Upload
     */
    public function image(string $title, string $value = ''): Upload
    {
        return self::uploadImage('image', $title, $this->uploadImageUrl(), $value)
                    ->uploadName('image')
                    ->data(['none' => ''])
                    ->headers($this->authorization());
    }

    /**
     * 多图
     *
     * @time 2021年03月03日
     * @param $title
     * @param array $value
     * @return \FormBuilder\UI\Elm\Components\Upload
     */
    public function images(string $title, array $value = []): Upload
    {
        return self::uploadImages('image', $title, $this->uploadImageUrl(), $value)
                    ->uploadName('image')
                    ->data(['none' => ''])
                    ->headers($this->authorization());
    }
}
