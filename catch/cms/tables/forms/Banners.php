<?php
namespace catchAdmin\cms\tables\forms;

use catchAdmin\cms\model\Category;
use catcher\library\form\Form;

class Banners extends Form
{
    public function fields(): array
    {
        $categories =  Category::field(['id', 'name', 'parent_id'])->select()->toTree();

        // TODO: Implement fields() method.
        return [
            self::cascader('category_id', '选择分类', [])
                ->options($categories)
                ->clearable(true)
                ->filterable(true)
                ->showAllLevels(false)
                ->props([
                    'props' => [
                        'value' => 'id',
                        'label' => 'name',
                        'checkStrictly' => true,
                        'multiple' => false,
                    ],
                ])->style(['width' => '100%']),

            self::input('title', '标题')->placeholder('请输入标题')->required(),

            self::image('图片', 'banner_img'),

            self::input('link_to', '外链')->appendValidate([
                self::validateUrl()
            ])
        ];
    }
}