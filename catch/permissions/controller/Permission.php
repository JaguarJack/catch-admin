<?php
namespace catchAdmin\permissions\controller;


use catcher\base\CatchRequest as Request;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\library\ParseClass;
use catcher\Tree;
use catchAdmin\permissions\model\Permissions;
use think\helper\Str;
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
        return CatchResponse::success($menuList->each(function (&$item) use ($buttonList, $children){
            $item[$children] = $buttonList[$item['id']] ?? [];
        })->toTree());
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
        // 按钮类型寻找上级
        if ($params['type'] == Permissions::BTN_TYPE && $parentId) {
            $permissionMark = $params['permission_mark'];
            // 查找父级
            $parentPermission = $this->permissions->findBy($parentId);
            // 如果父级是顶级 parent_id = 0
            if ($parentPermission->parent_id) {
                if (Str::contains($parentPermission->permission_mark, '@')) {
                    list($controller, $action) = explode('@', $parentPermission->permission_mark);
                    $permissionMark = $controller . '@' . $permissionMark;
                } else {
                    $permissionMark = $parentPermission->permission_mark .'@'. $permissionMark;
                }
            }
            $params['permission_mark'] = $permissionMark;
            $params['module'] = $parentPermission->module;
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

        $params = $request->param();
        // 按钮类型
        if ($params['type'] == Permissions::BTN_TYPE && $permission->parent_id) {
            $parentPermission = $this->permissions->findBy($permission->parent_id);

            $permissionMark = $params['permission_mark'];
            if ($parentPermission->parent_id) {
                if (Str::contains($parentPermission->permission_mark, '@')) {
                    list($controller, $action) = explode('@', $parentPermission->permission_mark);
                    $permissionMark = $controller . '@' . $permissionMark;
                } else {
                    $permissionMark = $parentPermission->permission_mark .'@'. $permissionMark;
                }
            }

            $params['permission_mark'] = $permissionMark;


            $this->permissions->where('id',$id)->update(array_merge($params, [
                'parent_id' => $permission->parent_id,
                'level'     => $permission->level,
                'updated_at' => time()
            ]));

            return CatchResponse::success();
        }

        $params = array_merge($request->param(), [
            'parent_id' => $permission->parent_id,
            'level'     => $permission->level
        ]);

        if ($permission->updateBy($id, $params)) {
            if ($params['module'] ?? false) {
                $this->permissions->updateBy($permission->id, [
                    'module' => $params['module'],
                ], 'parent_id');
            }
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
        $this->permissions->show($id);

        return CatchResponse::success();
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

        try {
            $methods = $parseClass->setModule('catch')->setRule($module, $controller)->onlySelfMethods();
            return CatchResponse::success($methods);
        }catch (\Exception $e) {
            return CatchResponse::success([]);
        }
    }
}


