<?php

namespace catchAdmin\cms\model;

class Forms extends BaseModel
{
    // 表名
    public $name = 'cms_forms';
    // 数据库字段映射
    public $field = array(
        'id',
        // 表单名称
        'name',
        // 表单别名
        'alias',
        // 表单提交的 URL
        'submit_url',
        // 表单标题
        'title',
        // 关键词
        'keywords',
        // 描述
        'description',
        // 成功提示信息
        'success_message',
        // 失败提示信息
        'failed_message',
        // 成功后跳转
        'success_link_to',
        // 1 需要 2 不需要
        'is_login_to_submit',
        // 创建人ID
        'creator_id',
        // 创建时间
        'created_at',
        // 更新时间
        'updated_at',
        // 软删除
        'deleted_at',
    );
}