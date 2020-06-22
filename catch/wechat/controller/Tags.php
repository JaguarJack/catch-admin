<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------

namespace catchAdmin\wechat\controller;

use think\Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\wechat\repository\WechatTagsRepository;

class Tags extends CatchController
{
    protected $repository;
    
    public function __construct(WechatTagsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 列表
     *
     * @time 2020/06/21 14:45
     *
     * @param Request $request
     * @return \think\Response
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function index(Request $request)
    {
        if ($request->has('all')) {
            return CatchResponse::success($this->repository->getList($request->param()));
        }

        return CatchResponse::paginate($this->repository->getList($request->param()));
    }
    
    /**
     * 保存
     *
     * @time 2020/06/21 14:45
     * @param Request Request 
     * @return \think\Response
     */
    public function save(Request $request)
    {
        return CatchResponse::success($this->repository->storeBy($request->post()));
    }
    
    /**
     * 读取
     *
     * @time 2020/06/21 14:45
     * @param $id 
     * @return \think\Response
     */
    public function read($id)
    {
       return CatchResponse::success($this->repository->findBy($id));
    }
    
    /**
     * 更新
     *
     * @time 2020/06/21 14:45
     * @param Request $request 
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        return CatchResponse::success($this->repository->updateBy($id, $request->post()));
    }
    
    /**
     * 删除
     *
     * @time 2020/06/21 14:45
     * @param $id 
     * @return \think\Response
     */
    public function delete($id)
    {
        return CatchResponse::success($this->repository->deleteBy($id));
    }

    public function sync()
    {
        return CatchResponse::success($this->repository->sync());
    }


}