<?php
namespace catchAdmin\permissions\model;

use catchAdmin\permissions\model\search\JobsSearch;
use catcher\base\CatchModel;

class Job extends CatchModel
{
    use JobsSearch;

    protected $name = 'jobs';
    
    protected $field = [
            'id', // 
			'job_name', // 岗位名称
			'coding', // 编码
			'creator_id', // 创建人ID
			'status', // 1 正常 2 停用
			'sort', // 排序字段
			'description', // 描述
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除状态，null 未删除 timestamp 已删除
    ];

  /**
   * 列表
   *
   * @time 2020年01月09日
   * @param $params
   * @throws \think\db\exception\DbException
   * @return \think\Paginator
   */
    public function getList()
    {
        return $this->catchSearch()
                    ->paginate();
    }
}
