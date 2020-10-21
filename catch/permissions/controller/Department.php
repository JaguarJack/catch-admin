<?php
namespace catchAdmin\permissions\controller;

use catcher\base\CatchController;
use catchAdmin\permissions\model\Department as DepartmentModel;
use catcher\base\CatchRequest;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\Tree;

class Department extends CatchController
{
    protected $department;

    public function __construct(DepartmentModel $department)
    {
        $this->department = $department;
    }

  /**
   * 列表
   *
   * @time 2020年01月09日
   * @param CatchRequest $request
   * @return \think\response\Json
   * @throws \think\db\exception\DbException
   */
    public function index(): \think\response\Json
    {
        return CatchResponse::success($this->department->getList());
    }

  /**
   * 保存
   *
   * @time 2020年01月09日
   * @param CatchRequest $request
   * @return \think\response\Json
   */
    public function save(CatchRequest $request): \think\response\Json
    {
        return CatchResponse::success($this->department->storeBy($request->param()));
    }

  /**
   * 更新
   *
   * @time 2020年01月09日
   * @param $id
   * @param CatchRequest $request
   * @return \think\response\Json
   */
    public function update($id, CatchRequest $request): \think\response\Json
    {
        return CatchResponse::success($this->department->updateBy($id, $request->param()));
    }

  /**
   * 删除
   *
   * @time 2020年01月09日
   * @param $id
   * @return \think\response\Json
   */
    public function delete($id): \think\response\Json
    {
        if ($this->department->where('parent_id', $id)->find()) {
            throw new FailedException('存在子部门，无法删除');
        }

        return CatchResponse::success($this->department->deleteBy($id));
    }
}
