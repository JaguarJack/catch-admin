<?php

namespace catchAdmin\member\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\member\model\Member as memberModel;

class Member extends CatchController
{
    protected $memberModel;

    /**
     * 允许更新字段
     *
     * @var array
     */
    protected $allow_field;

    public function __construct(MemberModel $memberModel)
    {
        $this->memberModel = $memberModel;
        $this->allow_field = $this->memberModel->allow_field();
    }

    /**
     * 获取布局
     * @time 2020年11月27日 06:13
     * @param Request $request
     */
    public function layout(): \think\Response
    {
        return CatchResponse::success($this->memberModel->getLayout());
    }

    /**
     * 列表
     * @time 2020年11月27日 06:12
     * @param Request $request
     */
    public function index(Request $request): \think\Response
    {
        // $this->getFields()
        return CatchResponse::paginate($this->memberModel->getList());
    }

    /**
     * 保存信息
     * @time 2020年11月27日 06:12
     * @param Request $request
     */
    public function save(Request $request): \think\Response
    {
        return CatchResponse::success($this->memberModel->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年11月27日 06:12
     * @param $id
     */
    public function read($id): \think\Response
    {
        return CatchResponse::success($this->memberModel->findBy($id));
    }

    /**
     * 更新
     * @time 2020年11月27日 06:12
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id): \think\Response
    {
        if (empty($this->allow_field))
            return CatchResponse::success(empty($this->allow_field), '222');
        // return json($this->allow_field);
        $data = $request->only($this->allow_field);
        // return json($id);
        // return json($data);
        return CatchResponse::success($this->memberModel->updateBy($id, $data));
    }

    /**
     * 删除
     * @time 2020年11月27日 06:12
     * @param $id
     */
    public function delete($id): \think\Response
    {
        return CatchResponse::success($this->memberModel->deleteBy($id));
    }
}
