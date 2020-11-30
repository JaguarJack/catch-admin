<?php

namespace catchAdmin\member\controller;

use think\facade\Db;
use catcher\CatchResponse;
use catcher\base\CatchController;
use catcher\base\CatchRequest as Request;
use catchAdmin\member\model\Level as levelModel;

class Level extends CatchController
{
    protected $levelModel;

    public function __construct(LevelModel $levelModel)
    {
        $this->levelModel = $levelModel;
    }
    /**
     * 获取布局
     * @time 2020年11月27日 06:13
     * @param Request $request
     */
    public function layout(): \think\Response
    {
        $fields =  Db::getFields('task_member_vip');
        $table = [];
        $form = [];
        $rules = [];
        $topSearch = [];
        foreach ($fields as $field) {
            $item = json_decode($field['comment'], true);
            if ($item) {
                if (count($item) >= 1) {
                    $table[$field['name']] = $item[0];
                }
                if (count($item) >= 2) {
                    $form[$field['name']] = $item[1];
                }
                if (count($item) >= 3) {
                    $rules[$field['name']] = $item[2];
                }
                $topSearch[] = [
                    'text' =>    $item[0]['text'],
                    'value' =>    $field['name'],
                ];
            }
        }
        $layout = [
            'tableDesc' => $table,
            'formDesc' => $form,
            'formRules' => $rules,
            'topButtons' => [],
            'rightButtons' => [],
            'topSearch' => $topSearch,
            'topTime' => 'create_time',
        ];
        return CatchResponse::success($layout);
    }
    /**
     * 列表
     * @time 2020年11月27日 06:13
     * @param Request $request
     */
    public function index(Request $request): \think\Response
    {
        return CatchResponse::paginate($this->levelModel->getList());
    }

    /**
     * 保存信息
     * @time 2020年11月27日 06:13
     * @param Request $request
     */
    public function save(Request $request): \think\Response
    {
        return CatchResponse::success($this->levelModel->storeBy($request->post()));
    }

    /**
     * 读取
     * @time 2020年11月27日 06:13
     * @param $id
     */
    public function read($id): \think\Response
    {
        return CatchResponse::success($this->levelModel->findBy($id));
    }

    /**
     * 更新
     * @time 2020年11月27日 06:13
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id): \think\Response
    {
        return CatchResponse::success($this->levelModel->updateBy($id, $request->post()));
    }

    /**
     * 删除
     * @time 2020年11月27日 06:13
     * @param $id
     */
    public function delete($id): \think\Response
    {
        return CatchResponse::success($this->levelModel->deleteBy($id));
    }
}
