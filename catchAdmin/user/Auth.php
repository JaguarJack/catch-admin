<?php
namespace catchAdmin\user;

use catchAdmin\permissions\model\Permissions;
use catchAdmin\user\model\Users;
use catcher\exceptions\LoginFailedException;
use think\facade\Session;

class Auth
{
    protected const USER_KEY = 'admin_user';

    /**
     * 登陆
     *
     * @time 2019年11月28日
     * @param $params
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws LoginFailedException
     */
    public static function login($params)
    {
        $user = Users::where('email', $params['email'])->find();

        if (!$user) {
            throw new LoginFailedException('登陆失败, 请检查用户名和密码');
        }

        if (!password_verify($params['password'], $user->password)) {
            throw new LoginFailedException('登陆失败, 请检查用户名和密码');
        }

        if ($user->status == Users::DISABLE) {
            throw new LoginFailedException('该用户已被禁用');
        }

        // 记录用户登录
        $user->last_login_ip = request()->ip();
        $user->last_login_time = time();
        $user->save();

        Session::set(self::getLoginUserKey(), $user);

        return true;
    }

    /**
     * 退出登陆
     *
     * @time 2019年11月28日
     * @return bool
     */
    public static function logout(): bool
    {
        Session::delete(self::getLoginUserKey());

        return true;
    }

    /**
     *
     * @time 2019年12月15日
     * @return mixed
     */
    public static function user()
    {
        return Session::get(self::getLoginUserKey(), null);
    }

    /**
     *
     * @time 2019年12月15日
     * @return string
     */
    protected static function getLoginUserKey(): string
    {
        return md5(self::USER_KEY);
    }

    /**
     *
     * @time 2019年12月15日
     * @param $mark
     * @param $module
     * @return bool
     */
    public static function hasPermissions($mark, $module): bool
    {
        $permissionIds = self::user()->get->getPermissionsBy();

        $permissionId = Permissions::where('module', $module)
                            ->where('permission_mark', $mark)->value('id');

        return in_array($permissionId, $permissionIds);
    }
}
