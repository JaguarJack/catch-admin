<?php
namespace catchAdmin\permissions\tables\forms;

use catchAdmin\permissions\model\Department as DepartmentModel;
use catcher\library\form\Form;

class Department extends Form
{
    public function fields(): array
    {
        return [
        // TODO: Implement fields() method
            Form::cascader('parent_id', '上级部门', [0])->options(
            DepartmentModel::field(['id', 'parent_id', 'department_name'])->select()->toTree()
            )->clearable(true)->filterable(true)->props([
                'props' => [
                    'value' => 'id',
                    'label' => 'department_name',
                    'checkStrictly' => true
                ],
            ])->style(['width' => '100%']),
            Form::input('department_name', '部门名称')->required()->placeholder('请输入部门名称'),
            Form::input('principal', '部门负责人'),
            Form::input('mobile', '负责人联系方式'),
            Form::email('email', '邮箱'),
            Form::radio('status', '状态')->value(1)->options(
                Form::options()->add('启用', 1)->add('禁用', 2)->render()
            ),
            Form::number('sort', '排序')->value(1)->min(1)->max(10000),
        ];
    }
}