<?php
/**
 * @filename WechatUsersRepository.php
 * @date     2020/6/7
 * @project  https://github.com/yanwenwu/catch-admin
 * @document http://doc.catchadmin.com
 * @author   JaguarJack <njphper@gmail.com>
 * @copyright By CatchAdmin
 * @license  https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt
 */
namespace catchAdmin\wechat\controller;

use catchAdmin\wechat\library\SyncWechatUsers;
use catchAdmin\wechat\repository\WechatUsersRepository;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\library\WeChat;
use catcher\Utils;
use think\facade\Console;
use think\Request;

class Users extends CatchController
{
    protected $user;

    public function __construct(WechatUsersRepository $users)
    {
        $this->user = $users;
    }

    /**
     * 列表
     *
     * @time 2020年06月19日
     * @return \think\response\Json
     */
    public function index()
    {
        return CatchResponse::paginate($this->user->getList());
    }

    /**
     * 备注
     *
     * @time 2020年06月19日
     * @param $id
     * @param $remark
     * @return \think\response\Json
     */
    public function remark($id, $remark)
    {
        return CatchResponse::success($this->user->remark($id, $remark));
    }

    /**
     * 拉黑
     *
     * @time 2020年06月19日
     * @param $id
     * @return \think\response\Json
     */
    public function block($id)
    {
        return CatchResponse::success($this->user->block($id));
    }

    /**
     * 贴标签
     *
     * @time 2020年06月26日
     * @param $id
     * @param Request $request
     * @return \think\response\Json
     */
    public function tag($id, Request $request)
    {
       return CatchResponse::success($this->user->tag($id, $request->post()));
    }

    /**
     * 用户同步
     *
     * @time 2020年06月26日
     * @param SyncWechatUsers $users
     * @return \think\response\Json
     */
    public function sync(SyncWechatUsers $users)
    {
        return CatchResponse::success($users->start(), 'success');
    }

    public function subscribe()
    {

    }

    public function unsubscribe()
    {

    }
}