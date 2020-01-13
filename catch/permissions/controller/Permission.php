<?php
namespace catchAdmin\permissions\controller;


use catcher\base\CatchRequest as Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\Tree;
use catchAdmin\permissions\model\Permissions as Permissions;

class Permission extends CatchController
{
    protected $permissions;

    public function __construct(Permissions $permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @return \think\response\Json
     */
    public function index()
    {
        return CatchResponse::success(Tree::done($this->permissions->getList()));
    }

    /**
     *
     * @time 2019年12月11日
     * @throws \Exception
     * @return string
     */
    public function create()
    {}

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request)
    {
        return CatchResponse::success($this->permissions->storeBy($request->param()));
    }

    public function read()
    {}

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @throws \Exception
     * @return string
     */
    public function edit($id)
    {}

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @param Request $request
     * @return \think\response\Json
     */
    public function update($id, Request $request)
    {
        return CatchResponse::success($this->permissions->updateBy($id, $request->param()));
    }

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @throws FailedException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return \think\response\Json
     */
    public function delete($id)
    {
        if ($this->permissions->where('parent_id', $id)->find()) {
            throw new FailedException('存在子菜单，无法删除');
        }

        $this->permissions->findBy($id)->roles()->detach();

        return CatchResponse::success($this->permissions->deleteBy($id));
    }
}


