<?php
namespace catchAdmin\permissions\model;

use catchAdmin\permissions\model\search\DepartmentSearch;
use catcher\base\CatchModel;
use think\db\exception\DbException;

class Department extends CatchModel
{
    use DepartmentSearch;

    protected $name = 'departments';
    
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
   * @return array
   * @throws DbException
   */
    public function getList(): array
    {
        return $this->catchSearch()
                    ->catchOrder()
                    ->select()->toTree();
    }

    /**
     * 获取子部门IDS
     *
     * @time 2020年11月04日
     * @param $id
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @return mixed
     */
    public static function getChildrenDepartmentIds($id)
    {
        $departmentIds = Department::field(['id', 'parent_id'])->select()->getAllChildrenIds([$id]);

        $departmentIds[] = $id;

        return $departmentIds;
    }
}
