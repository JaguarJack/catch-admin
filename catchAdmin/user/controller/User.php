<?php
namespace catchAdmin\user\controller;

use catchAdmin\user\model\Users;
use catchAdmin\user\request\CreateRequest;
use catchAdmin\user\request\UpdateRequest;
use catcher\base\BaseController;
use catcher\CatchForm;
use catcher\CatchResponse;

class User extends BaseController
{
    protected $user;

    public function __construct(Users $user)
    {
       $this->user = $user;
    }

    /**
     *
     * @time 2019年12月04日
     * @throws \Exception
     * @return string
     */
    public function index()
    {
        $form = new CatchForm();

        $form->text('name', '用户名')->id('id')->class('class');
        $form->select('names', '性别')->options(['请选择性别', '男', '女'])->default(1);
        //$form->select('namess', '用户名');

        $form->render();
        return $this->fetch([
            'form' => $form->render()
        ]);
    }

    /**
     *
     * @time 2019年12月04日
     * @param CreateRequest $request
     * @return \think\response\Json
     */
    public function create(CreateRequest $request)
    {
        return CatchResponse::success($this->user->storeBy($request->post()));
    }

    public function save()
    {}

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return \think\response\Json
     */
    public function read($id)
    {
        return CatchResponse::success($this->user->findBy($id));
    }

    public function edit()
    {}

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @param UpdateRequest $request
     * @return \think\response\Json
     */
    public function update($id, UpdateRequest $request)
    {
        return CatchResponse::success($this->user->updateBy($id, $request->post()));
    }

    /**
     *
     * @time 2019年12月04日
     * @param $id
     * @return \think\response\Json
     */
    public function delete($id)
    {
        return CatchResponse::success($this->user->deleteBy($id));
    }

}