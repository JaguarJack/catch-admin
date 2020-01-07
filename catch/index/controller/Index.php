<?php
namespace catchAdmin\index\controller;

use catchAdmin\permissions\model\Permissions;
use catchAdmin\user\Auth;
use catcher\base\CatchController;
use catcher\CatchAuth;
use catcher\Tree;
use think\facade\Db;

class Index extends CatchController
{
    /**
     *
     * @time 2019年12月11日
     * @return string
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     */
    public function index(): string
    {
        $user = (new CatchAuth())->user();

        $permissionIds = $user->getPermissionsBy();

        $menus = Permissions::whereIn('id', $permissionIds)
                    ->where('type', Permissions::MENU_TYPE)
                    ->field(['id', 'parent_id', 'permission_name', 'route'])
                    ->select()->toArray();

        return $this->fetch([
            'menus' => Tree::done($menus),
            'username' => $user->username,
        ]);
    }

    /**
     *
     * @time 2019年12月11日
     * @throws \Exception
     * @return string
     */
    public function theme(): string
    {
        return $this->fetch();
    }

    /**
     *
     * @time 2019年12月12日
     * @throws \Exception
     * @return string
     */
    public function dashboard(): string
    {
        $mysqlVersion = Db::query('select version() as version');
        return $this->fetch([
            'mysql_version' => $mysqlVersion['0']['version'],
        ]);
    }
}
