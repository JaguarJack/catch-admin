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

  /**
   * 列表数据
   *
   * @time 2020年01月09日
   * @param $params
   * @throws \think\db\exception\DbException
   */
    public function getList($params)
    {
        return $this->when($params['department_name'] ?? false, function ($query) use ($params){
                          $query->whereLike('department_name', '%' . $params['department_name'] . '%');
                      })
                    ->when($params['status'] ?? false, function ($query) use ($params){
                          $query->where('status', $params['status']);
                    })
                    ->select()->toArray();
    }
}
