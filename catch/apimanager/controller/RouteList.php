<?php

namespace catchAdmin\apimanager\controller;

use catcher\base\CatchRequest as Request;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catchAdmin\apimanager\repository\RouteListRepository as RouteListModel;
use think\Response;

class RouteList extends CatchController
{
    
    protected $routeListModel;
    
    /**
     *
     * @time 2021/11/11 17:47
     * @param RouteListModel $routeListModel
     * @return mixed
     */
    public function __construct(RouteListModel $routeListModel)
    {
        $this->routeListModel = $routeListModel;
    }
    
    /**
     *
     * @time 2021/11/11 17:47
     * @return Response
     */
    public function index() : Response
    {
        return CatchResponse::paginate($this->routeListModel->getList());
    }
    
    /**
     *
     * @time 2021/11/11 17:47
     * @param Request $request
     * @return Response
     */
    public function save(Request $request) : Response
    {
        return CatchResponse::success($this->routeListModel->storeBy($request->post()));
    }
    
    /**
     *
     * @time 2021/11/11 17:47
     * @param $id
     * @return Response
     */
    public function read($id) : Response
    {
        return CatchResponse::success($this->routeListModel->findBy($id));
    }
    
    /**
     *
     * @time 2021/11/11 17:47
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request) : Response
    {
        return CatchResponse::success($this->routeListModel->updateBy($id, $request->post()));
    }
    
    /**
     *
     * @time 2021/11/11 17:47
     * @param $id
     * @return Response
     */
    public function delete($id) : Response
    {
        return CatchResponse::success($this->routeListModel->deleteBy($id));
    }

    /**
     * 同步
     *
     * @time 2021/11/11 17:47
     * @return \think\response\Json
     * @throws \Exception
     */
    public function sync()
    {
        return CatchResponse::success($this->routeListModel->sync());
    }
}