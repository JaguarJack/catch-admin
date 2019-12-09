<?php
namespace catchAdmin\permissions\controller;

use app\Request;
use catcher\base\BaseController;
use catcher\CatchForm;
use catcher\CatchResponse;

class Roles extends BaseController
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

    public function roles()
    {

    }

    public function create()
    {
        $form = new CatchForm();
        $form->formId('roles');
        $form->text('name', '角色名称');
        $form->formBtn('submitRoles');

        return $this->fetch([
            'form' => $form->render()
        ]);
    }

    public function save()
    {}

    public function read($id)
    {

    }

    public function edit($id)
    {
        $form = new CatchForm();
        $form->formId('roles');
        $form->text('name', '角色名称');
        $form->formBtn('submitRoles');

        return $this->fetch([
            'form' => $form->render()
        ]);
    }

    public function update($id, UpdateRequest $request)
    {
    }

    public function delete($id)
    {
        return CatchResponse::success($this->role->deleteBy($id));
    }

    public function list(Request $request)
    {
        return CatchResponse::paginate($this->role->getList($request->param()));
    }
}