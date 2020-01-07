<?php
namespace catchAdmin\user;

use catchAdmin\permissions\model\Permissions;
use catchAdmin\permissions\model\Roles;
use catchAdmin\user\model\Users;
use catcher\exceptions\LoginFailedException;
use catcher\Tree;
use thans\jwt\facade\JWTAuth;
use think\facade\Session;

class Auth
{
    protected const USER_ID = 'catch_uid';
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



        // Session::set(self::getLoginUserKey(), $user);

        return JWTAuth::builder([self::USER_ID => $user->id]);
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
        $user = Users::where('id', JWTAuth::auth()[self::USER_ID])
                     ->field(['id', 'username', 'status'])->find();

        return $user;
    }

    public static function getUserInfo()
    {
        $user = self::user();

        $roles = $user->getRoles();

        $user->permissions = Permissions::whereIn('id', $user->getPermissionsBy())
                                  ->field(['permission_name as title', 'route', 'icon'])
                                  ->select();

        $user->roles = $roles;

        return $user;
    }

    /**
     *
     * @time 2019年12月15日
     * @return string
     */
    protected static function getLoginUserKey(): string
    {
        // return md5(self::USER_KEY);
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
