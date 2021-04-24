<?php
namespace catchAdmin\permissions\tables\forms;

use catchAdmin\permissions\model\Department as DepartmentModel;
use catchAdmin\permissions\model\Permissions;
use catchAdmin\permissions\model\Roles;
use catcher\library\form\Form;

class Role extends Form
{
    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            Form::cascader('parent_id', '上级角色', [])->options(
                Roles::field(['id', 'parent_id', 'role_name'])->select()->toTree()
            )->clearable(true)->filterable(true)->props([
                'props' => [
                    'value' => 'id',
                    'label' => 'role_name',
                    'checkStrictly' => true
                ],
            ])->style(['width' => '100%']),

            self::input('role_name', '角色名称')->required()
                ->clearable(true)->placeholder('请填写角色名称'),

            self::input('identify', '角色标识')
                ->clearable(true)->required()
                ->placeholder('请填写角色标识'),

            self::textarea('description', '角色描述')
                ->clearable(true)->placeholder('请填写角色描述'),

            self::tree('_permissions', '角色权限', [])
                ->props(self::props('permission_name', 'id', [],
                    Permissions::field(['id', 'parent_id', 'permission_name'])->select()->toTree()
                ))
                ->required(),

            self::select('data_range', '数据权限')
                ->placeholder('请选择数据权限')
                ->options(
                    self::options()->add('请选择数据权限', 0)
                                    ->add('全部数据权限', Roles::ALL_DATA)
                                    ->add('自定义数据权限', Roles::SELF_CHOOSE)
                                    ->add('仅本人数据权限', Roles::SELF_DATA)
                                    ->add('本部门数据权限', Roles::DEPARTMENT_DATA)
                                    ->add('部门以及以下数据权限', Roles::DEPARTMENT_DOWN_DATA)
                                    ->render()
                )->style(['width' => '100%'])
                ->appendControl(Roles::SELF_CHOOSE, [
                    self::cascader('departments', '自定义权限')
                            ->options(
                                DepartmentModel::field(['id', 'parent_id', 'department_name'])->select()->toTree()
                            )
                             ->props(self::props('department_name', 'id', [
                                 'multiple' => true,
                                 'emitPath' => false
                             ]))
                             ->showAllLevels(false)
                             ->style(['width' => '100%']),
                ])
        ];
    }

}