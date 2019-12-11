<?php
namespace catchAdmin\permissions\controller;

use app\Request;
use catcher\base\CatchController;
use catcher\CatchForm;
use catcher\CatchResponse;
use catcher\exceptions\FailedException;
use catcher\Tree;

class Roles extends CatchController
{
    protected $role;

    public function __construct(\catchAdmin\permissions\model\Roles $role)
    {
        $this->role = $role;
    }

    /**
     *
     * @time 2019年12月09日
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
     * @throws \Exception
     * @return string
     */
    public function create()
    {
        $form = new CatchForm();
        $form->formId('role');
        $form->text('role_name', '角色名称', true)->verify('required')->placeholder('请输入角色名称');
        $form->hidden('parent_id')->default(\request()->param('id') ?? 0);
        $form->textarea('description', '角色描述')->placeholder('请输入角色描述');
        $form->dom('<div id="permissions"></div>', '权限');
        $form->formBtn('submitRole');

        return $this->fetch([
            'form' => $form->render()
        ]);
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @return \think\response\Json
     */
    public function save(Request $request)
    {
        // 添加角色
        dd($request->param('roleids'));
        return CatchResponse::success($this->role->storeBy($request->param()));
    }

    public function read($id)
    {

    }

    /**
     *
     * @time 2019年12月11日
     * @param $id
     * @throws \Exception
     * @return string
     */
    public function edit($id)
    {
        $role = $this->role->findBy($id);

        $form = new CatchForm();
        $form->formId('role');
        $form->hidden('parent_id')->default($role->parent_id);
        $form->text('role_name', '角色名称', true)->default($role->name)->verify('required')->placeholder('请输入角色名称');
        $form->textarea('description', '角色描述')->default($role->description)->placeholder('请输入角色描述');
        $form->formBtn('submitRole');

        return $this->fetch([
            'form' => $form->render(),
            'role_id' => $role->id,
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
        return CatchResponse::success($this->role->updateBy($id, $request->param()));
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
        if ($this->role->where('parent_id', $id)->find()) {
            throw new FailedException('存在子角色，无法删除');
        }

        // 删除权限
        return CatchResponse::success($this->role->deleteBy($id));
    }

    /**
     *
     * @time 2019年12月11日
     * @param Request $request
     * @return \think\response\Json
     */
    public function list(Request $request)
    {
        return CatchResponse::success(Tree::done($this->role->getList($request->param())));
    }
}