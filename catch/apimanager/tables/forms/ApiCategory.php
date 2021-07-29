<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------

namespace catchAdmin\apimanager\tables\forms;

use catchAdmin\apimanager\model\ApiCategory as ApiCategoryModel;
use catcher\library\form\Form;

class ApiCategory extends Form
{
    public function fields(): array
    {
        return [
            // TODO: Implement fields() method
            Form::cascader('parent_id', '上级分类', [0])->options(
                ApiCategoryModel::field(['id', 'parent_id', 'category_title'])->select()->toTree()
            )->clearable(true)->filterable(true)->props([
                'props' => [
                    'value' => 'id',
                    'label' => 'category_title',
                    'checkStrictly' => true
                ],
            ])->style(['width' => '100%']),
            Form::input('category_title', '分类标题')->required()->placeholder('分类标题'),
            Form::input('category_name', '分类唯一标识'),
            Form::radio('status', '状态')->value(1)->options(
                Form::options()->add('启用', 1)->add('禁用', 2)->render()
            ),
            Form::number('sort', '排序')->value(1)->min(1)->max(10000),
        ];
    }
}