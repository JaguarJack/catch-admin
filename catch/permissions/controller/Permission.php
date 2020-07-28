<?php
namespace catchAdmin\permissions\controller;


use catcher\base\CatchRequest as Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\library\ParseClass;
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
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function index(Request $request): Json
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

        // 子节点的 key
        $children = $request->param('actionList') ?? 'children';
        // 返回树结构
        return CatchResponse::success(Tree::done($menuList->each(function (&$item) use ($buttonList, $children){
            $item[$children] = $buttonList[$item['id']] ?? [];
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

        $params = array_merge($request->param(), [
            'parent_id' => $permission->parent_id,
            'level'     => $permission->level
        ]);

        if ($permission->updateBy($id, $params) && $this->permissions->updateBy($permission->id, [
                'module' => $params['module'] ? $params['module'] : $permission->module
            ], 'parent_id')) {
            return CatchResponse::success();
        }

       throw new FailedException('更新失败');
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

    /**
     * 显示/隐藏
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/5/19
     * @param $id
     * @return Json
     */
    public function show($id)
    {
        $permission = $this->permissions->findBy($id);

        $permission->status = $permission->status == Permissions::ENABLE ? Permissions::DISABLE : Permissions::ENABLE;

        if ($permission->save()) {
            $this->permissions->where('parent_id', $id)->update([
                'status' => $permission->status,
            ]);
        }

        return CatchResponse::success($permission->save());
    }

    /**
     *
     * @time 2020年06月05日
     * @param $id
     * @param ParseClass $parseClass
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @return Json
     */
    public function getMethods($id, ParseClass $parseClass)
    {
        $permission = Permissions::where('id', $id)->find();

        $module = $permission->module;

        $controller = explode('@', $permission->permission_mark)[0];

        $methods = $parseClass->setModule('catch')->setRule($module, $controller)->onlySelfMethods();

        return CatchResponse::success($methods);
    }
}


