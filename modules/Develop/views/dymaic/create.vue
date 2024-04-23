<template>
    <div>
        <CatchForm :schema="form" @onSubmit="submit"/>
    </div>
</template>

<script lang="ts" setup>
const form = {
    items: [
        {
            name: 'Grid_FhAuLh',
            props: {
                columns: 2
            },
            component: 'grid',
            children: [
                {
                    name: 'Grid_qRvhpR',
                    props: {
                        columns: 1
                    },
                    component: 'grid',
                    children: [
                        {
                            name: 'type',
                            props: {
                                clearable: true,
                                options: [
                                    {
                                        value: 1,
                                        label: '目录'
                                    },
                                    {
                                        value: 2,
                                        label: '菜单'
                                    },
                                    {
                                        value: 3,
                                        label: '按钮'
                                    }
                                ],
                                optionType: 'button'
                            },
                            label: '菜单类型',
                            component: 'radio',
                            class: 'mt-4',
                            initialValue: 1
                        },
                        {
                            name: 'permission_name',
                            props: {
                                clearable: true
                            },
                            label: '菜单名称',
                            component: 'input',
                            class: 'mt-4',
                            required: true
                        },
                        {
                            name: 'module',
                            props: {
                                clearable: true,
                                options: [
                                    {
                                        label: '权限管理',
                                        value: 'permissions'
                                    },
                                    {
                                        label: '动态表单',
                                        value: 'test'
                                    },
                                    {
                                        label: 'Testss',
                                        value: 'tests'
                                    }
                                ]
                            },
                            label: '所属模块',
                            component: 'select',
                            class: 'mt-4',
                            required: true,
                            hidden: '{{$values.type == 3}}'
                        },
                        {
                            name: 'route',
                            props: {
                                clearable: true
                            },
                            label: '路由Path',
                            component: 'input',
                            class: 'mt-4',
                            required: true,
                            hidden: '{{$values.type == 3}}'
                        },
                        {
                            name: 'redirect',
                            props: {
                                clearable: true
                            },
                            label: '重定向',
                            component: 'input',
                            class: 'mt-4',
                            hidden: '{{$values.type == 3}}'
                        },
                        {
                            name: 'sort',
                            props: {
                                clearable: true,
                                min: 1,
                                max: 10000
                            },
                            label: '排序',
                            component: 'input_number',
                            class: 'mt-4',
                            initialValue: 1
                        }
                    ]
                },
                {
                    name: 'Grid',
                    props: {
                        columns: 1
                    },
                    component: 'grid',
                    children: [
                        {
                            name: 'parent_id',
                            props: {
                                clearable: true,
                                options: [
                                    {
                                        id: 1,
                                        parent_id: 0,
                                        permission_name: '权限管理',
                                        children: [
                                            {
                                                id: 2,
                                                parent_id: 1,
                                                permission_name: '角色管理'
                                            },
                                            {
                                                id: 8,
                                                parent_id: 1,
                                                permission_name: '菜单管理'
                                            },
                                            {
                                                id: 15,
                                                parent_id: 1,
                                                permission_name: '岗位管理'
                                            },
                                            {
                                                id: 22,
                                                parent_id: 1,
                                                permission_name: '部门管理'
                                            },
                                            {
                                                id: 29,
                                                parent_id: 1,
                                                permission_name: '创建菜单'
                                            }
                                        ]
                                    },
                                    {
                                        id: 30,
                                        parent_id: 0,
                                        permission_name: '测试',
                                        children: [
                                            {
                                                id: 31,
                                                parent_id: 30,
                                                permission_name: '测试看看'
                                            },
                                            {
                                                id: 37,
                                                parent_id: 30,
                                                permission_name: '创建'
                                            }
                                        ]
                                    }
                                ],
                                label: 'permission_name',
                                valueKey: 'id',
                                value: 'id',
                                'show-all-levels': false,
                                checkStrictly: true,
                                class: 'w-full'
                            },
                            label: '上级菜单',
                            component: 'cascader',
                            class: 'mt-4'
                        },
                        {
                            name: 'permission_mark',
                            props: {
                                clearable: true,
                                api: 'options/controllers',
                                query: {
                                    module: '{{ $values.module }}'
                                }
                            },
                            label: '权限标识',
                            component: '{{$values.type == 3 ? "input" : "select"}}',
                            class: 'mt-4',
                            hidden: '{{$values.type == 1}}',
                            required: true
                        },
                        {
                            name: 'icon',
                            props: {
                                clearable: true
                            },
                            label: '图标',
                            component: 'icon_select',
                            class: 'mt-4',
                            hidden: '{{$values.type == 3}}'
                        },
                        {
                            name: 'component',
                            props: {
                                clearable: true,
                                "allow-create": true
                            },
                            label: '所属组件',
                            component: 'select',
                            class: 'mt-4',
                            hidden: '{{$values.type == 3}}'
                        },
                        {
                            name: 'hidden',
                            props: {
                                options: [
                                    {
                                        value: 1,
                                        label: '是'
                                    },
                                    {
                                        value: 2,
                                        label: '否'
                                    }
                                ]
                            },
                            label: '是否隐藏',
                            component: 'radio',
                            class: 'mt-4',
                            initialValue: 2,
                            hidden: '{{$values.type == 3}}'
                        },
                        {
                            name: 'keepalive',
                            props: {
                                options: [
                                    {
                                        value: 1,
                                        label: '是'
                                    },
                                    {
                                        value: 2,
                                        label: '否'
                                    }
                                ]
                            },
                            label: '是否缓存',
                            component: 'radio',
                            class: 'mt-4',
                            initialValue: 2,
                            hidden: '{{$values.type == 3}}'
                        }
                    ]
                }
            ]
        }
    ]
}
// @ts-nocheck
/**import { useCreate } from '@/form/composables/useCreate'
const props = defineProps({
    primary: [String, Number],
    api: String,
})


const { form } = useCreate(props.api, props.primary)
*/
const submit = (formData) => {
    console.log(formData)
}
</script>
