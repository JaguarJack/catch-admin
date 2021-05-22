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

use catchAdmin\cms\model\Category as CategoryModel;
use catcher\library\form\Form;

class Category extends BaseForm
{
    protected $table = 'cms_category';

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
            Form::input('name', '分类名称')->required(),
            Form::cascader('parent_id', '父级栏目')
                ->options(CategoryModel::field(['id', 'name', 'parent_id'])->select()->toTree())->props([
                    'props' => [
                        'value' => 'id',
                        'label' => 'name',
                        'checkStrictly' => true
                    ]
            ])->showAllLevels(false)->style(['width' => '100%']),

            Form::input('title', 'seo标题'),
            Form::input('keywords', 'seo关键词'),
            Form::textarea('description', 'seo描述'),
            Form::input('url', '自定义URL'),
            Form::select('type', '页面类型', 1)->options([
                ['value' => 1, 'label' => '列表模式'],
                ['value' => 2, 'label' => '单页模式'],
                ['value' => 3, 'label' => '封面模式'],
            ]),

            Form::radio('status', '状态', 1)->options(
                self::options()->add('启用', 1)
                    ->add('禁用', 2)->render()
            ),

            Form::radio('is_can_comment', '评论', 2)->options(
                self::options()->add('可以', 1)
                                ->add('不可以', 2)->render()
            ),

            Form::radio('is_can_contribute', '投稿', 2)->options(
                self::options()->add('可以', 1)
                    ->add('不可以', 2)->render()
            ),

            Form::number('weight', '权重', 1),

            Form::number('limit', '每页数量', 10),
        ];
    }
}