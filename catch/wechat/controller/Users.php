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

use catchAdmin\wechat\repository\WechatUsersRepository;
use catcher\base\CatchController;
use catcher\CatchResponse;
use catcher\Utils;
use think\facade\Console;

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

    public function sync()
    {
        Console::call('sync:users');

        return CatchResponse::success('', 'success');
    }

    public function subscribe()
    {

    }

    public function unsubscribe()
    {

    }
}