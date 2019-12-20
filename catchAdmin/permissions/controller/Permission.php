<?php
namespace catchAdmin\permissions\controller;


use app\Request;
use catcher\base\CatchController;
use catcher\CatchAdmin;
use catcher\CatchForm;
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
     * @throws \Exception
     * @return string
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @return \think\response\Json
     */
    public function list(Request $request)
    {
        return CatchResponse::success(Tree::done($this->permissions->getList($request->param())));
    }

    /**
     *
     * @time 2019年12月11日
     * @throws \Exception
     * @return string
     */
    public function create()
    {
        $form = new CatchForm();
        $form->formId('permission');
        $form->text('permission_name', '菜单名称', true)->verify('required')->placeholder('请输入菜单名称');
        $form->hidden('parent_id')->default(\request()->param('id') ?? 0);
        $form->select('module', '模块', true)->verify('required')->options(CatchAdmin::getModulesInfo());
        $form->text('route', '路由')->placeholder('请输入路由');
        $form->radio('method', '请求方法', true)->default(Permissions::GET)->options([
            ['value' => Permissions::GET, 'title' => 'get'],
            ['value' => Permissions::POST, 'title' => 'post'],
            ['value' => Permissions::PUT, 'title' => 'put'],
            ['value' => Permissions::DELETE, 'title' => 'delete'],
        ]);
        $form->text('permission_mark', '权限标识', true)->verify('required')->placeholder('请输入权限标识controller:action');
        $form->radio('type', '类型', true)->default(Permissions::BTN_TYPE)->options([
            ['value' => Permissions::MENU_TYPE, 'title' => '菜单'],
            ['value' => Permissions::BTN_TYPE, 'title' => '按钮'],
        ]);
        $form->text('sort', '排序')->verify('numberX')->default(1)->placeholder('倒叙排序');
        $form->formBtn('submitPermission');

        return $this->fetch(['form' => $form->render()]);
    }

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
    {
        $permission = $this->permissions->findBy($id);

        $form = new CatchForm();
        $form->formId('permission');
        $form->text('permission_name', '菜单名称', true)
             ->default($permission->permission_name)
             ->verify('required')
             ->placeholder('请输入菜单名称');
        $form->hidden('parent_id')->default($permission->parent_id);
        $form->select('module', '模块', true)->default($permission->module)->options(CatchAdmin::getModulesInfo());
        $form->text('route', '路由')->default($permission->route)->placeholder('请输入路由');
        $form->radio('method', '请求方法', true)->verify('required')->default($permission->method)->options([
            ['value' => Permissions::GET, 'title' => 'get'],
            ['value' => Permissions::POST, 'title' => 'post'],
            ['value' => Permissions::PUT, 'title' => 'put'],
            ['value' => Permissions::DELETE, 'title' => 'delete'],
        ]);
        $form->text('permission_mark', '权限标识', true)
             ->default($permission->permission_mark)
             ->verify('required')->placeholder('请输入权限标识controller:action');
        $form->radio('type', '类型', true)->default($permission->type)->options([
            ['value' => Permissions::MENU_TYPE, 'title' => '菜单'],
            ['value' => Permissions::BTN_TYPE, 'title' => '按钮'],
        ]);
        $form->text('sort', '排序')->verify('numberX')->default($permission->sort)->placeholder('倒叙排序');
        $form->formBtn('submitPermission');

        return $this->fetch([
            'form' => $form->render(),
            'permission_id' => $permission->id,
        ]);
    }

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


