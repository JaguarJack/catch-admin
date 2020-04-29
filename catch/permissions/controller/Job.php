<?php
namespace catchAdmin\permissions\controller;

use catchAdmin\permissions\model\Job as JobModel;
use catcher\base\CatchController;
use catcher\base\CatchRequest;
use catcher\CatchResponse;

class Job extends CatchController
{
  protected $job;

  public function __construct(JobModel $job)
  {
    $this->job = $job;
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
    return CatchResponse::paginate($this->job->getList());
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
    return CatchResponse::success($this->job->storeBy($request->post()));
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
    return CatchResponse::success($this->job->updateBy($id, $request->post()));
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
    return CatchResponse::success($this->job->deleteBy($id));
  }

/**
 * 获取所有
 *
 * @return \think\response\Json
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 */
  public function getAll()
  {
      return CatchResponse::success($this->job->field(['id', 'job_name'])->select());
  }
}
