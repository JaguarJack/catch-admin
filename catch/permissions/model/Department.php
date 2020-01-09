<?php
namespace catchAdmin\permissions\model;

use catcher\base\CatchModel;

class Department extends CatchModel
{
    protected $name = 'department';
    
    protected $field = [
            'id', // 
			'department_name', // 部门名称
			'parent_id', // 父级ID
			'principal', // 负责人
			'mobile', // 联系电话
			'email', // 联系又想
			'creator_id', // 创建人ID
			'status', // 1 正常 2 停用
			'sort', // 排序字段
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，null 未删除 timestamp 已删除
			   
    ];  
}