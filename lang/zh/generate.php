<?php

declare(strict_types=1);

return [
    'code' => [
        'title' => '生成代码',
        'module' => [
            'name' => '模块',
            'placeholder' => '请选择模块',
            'verify' => '请选择模块',
        ],
        'controller' => [
            'name' => '控制器',
            'placeholder' => '请输入控制器名称',
            'verify' => '请输入控制器名称',
        ],
        'model' => [
            'name' => '模型',
            'placeholder' => '请输入模型名称',
            'verify' => '请输入模型名称',
        ],
        'paginate' => [
            'name' => '分页',
            'placeholder' => '控制列表是否使用分页功能',
        ],
        'menu' => [
            'name' => '菜单名称',
            'placeholder' => '请输入菜单名称, 如果不填写, 则默认不会生成',
            'verify' => '请输入菜单名称',
        ],

        'operation' => [
            'name' => '表格操作',
            'import' => '导入数据',
            'export' => '导出数据',
        ],

        'gen' => [
            'form' => '生成表单',
            'dymaic' => '动态表单',
            'dialogForm' => '弹窗表单',
        ],
    ],

    'schema' => [
        'title' => '创建数据表',
        'name' => '表名称',
        'name_verify' => '请输入表名称',
        'engine' => [
            'name' => '表引擎',
            'verify' => '请选择表引擎',
            'placeholder' => '选择表引擎',
        ],
        'default_field' => [
            'name' => '默认字段',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'creator' => '创建人',
            'delete_at' => '软删除',
        ],
        'comment' => [
            'name' => '表注释',
            'verify' => '请填写表注释/说明',
        ],
        'structure' => [
            'title' => '创建数据结构',
            'field_name' => [
                'name' => '字段名称',
                'verify' => '请填写字段名称',
            ],
            'length' => '字段长度',
            'type' => [
                'name' => '字段类型',
                'placeholder' => '选择字段类型',
                'verify' => '请先选择字段类型',
            ],
            'form_label' => '表单 Label',
            'form_component' => '表单组件',
            'render' => '渲染位置',
            'list' => '列表',
            'form' => '表单',
            'export' => '导出',
            'import' => '导入',
            'unique' => '是否唯一',
            'search' => '查询',
            'search_op' => [
                'name' => '搜索操作符',
                'placeholder' => '选择搜索操作符',
            ],
            'nullable' => '是否为空',
            'default' => '默认值',
            'rules' => [
                'name' => '验证规则',
                'placeholder' => '选择验证规则',
            ],
            'operate' => '操作',
            'comment' => '字段注释',
        ],
    ],
];
