<?php
namespace catchAdmin\permissions\controller;


use catcher\base\CatchRequest as Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\Tree;
use catchAdmin\permissions\model\Permissions;
use think\response\Json;

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
     * @return Json
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function index(): Json
    {
        // 获取菜单类型
        $menuList = $this->permissions->getList(true);

        // 获取按钮类型并且重新排列
        $buttonList = [];
        $this->permissions
             ->whereIn('parent_id', array_unique($menuList->column('id')))
             ->where('type', Permissions::BTN_TYPE)
             ->select()->each(function ($item) use (&$buttonList){
                 $buttonList[$item['parent_id']][] = $item->toArray();
             });

        // 返回树结构
        return CatchResponse::success(Tree::done($menuList->each(function (&$item) use ($buttonList){
            $item['children'] = $buttonList[$item['id']] ?? [];
        })->toArray()));
    }

  /**
   *
   * @time 2019年12月11日
   * @param Request $request
   * @return Json
   * @throws \think\db\exception\DbException
   * @throws \think\db\exception\ModelNotFoundException
   * @throws \think\db\exception\DataNotFoundException
   */
    public function save(Request $request): Json
    {
        $params = $request->param();

        // 如果是子分类 自动写入父类模块
        $parentId = $params['parent_id'] ?? 0;
        if ($parentId) {
            $parent = $this->permissions->findBy($parentId);
            $params['module'] = $parent->module;
        }

        return CatchResponse::success($this->permissions->storeBy($params));
    }

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @param Request $request
     * @return Json
     */
    public function update($id, Request $request): Json
    {
        $permission = $this->permissions->findBy($id);

        // 如果是父分类需要更新所有子分类的模块
        if (!$permission->parent_id) {
            $this->permissions->updateBy($permission->parent_id, [
              'module' => $permission->module,
            ], 'parent_id');
        }

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
     * @return Json
     */
    public function delete($id): Json
    {
        if ($this->permissions->where('parent_id', $id)->find()) {
            throw new FailedException('存在子菜单，无法删除');
        }

        $this->permissions->findBy($id)->roles()->detach();

        return CatchResponse::success($this->permissions->deleteBy($id));
    }
}


