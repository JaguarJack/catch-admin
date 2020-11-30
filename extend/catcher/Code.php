<?php
declare(strict_types=1);

namespace catcher;

class Code
{
    public const SUCCESS = 10000; // 成功
    public const LOST_LOGIN = 10001; //  登录失效
    public const VALIDATE_FAILED = 10002; // 验证错误
    public const PERMISSION_FORBIDDEN = 10003; // 权限禁止
    public const LOGIN_FAILED = 10004; // 登录失败
    public const FAILED = 10005; // 操作失败
    public const LOGIN_EXPIRED = 10006; // 登录失效
    public const LOGIN_BLACKLIST = 10007; // 黑名单
    public const USER_FORBIDDEN = 10008; // 账户被禁

    public const WECHAT_RESPONSE_ERROR = 40000;
}
