<?php
namespace catchAdmin\permissions\middleware;

use catcher\Code;
use catcher\exceptions\FailedException;
use thans\jwt\exception\TokenBlacklistException;
use thans\jwt\exception\TokenExpiredException;
use thans\jwt\exception\TokenInvalidException;
use thans\jwt\facade\JWTAuth;
use think\Middleware;

class AuthTokenMiddleware extends Middleware
{
    public function handle($request, \Closure $next)
    {
       try {
          JWTAuth::auth();
       } catch (\Exception $e) {
           if ($e instanceof TokenExpiredException) {
               throw new FailedException('token 过期', Code::LOGIN_EXPIRED);
           }
           if ($e instanceof TokenBlacklistException) {
               throw new FailedException('token 被加入黑名单', Code::LOGIN_BLACKLIST);
           }
           if ($e instanceof TokenInvalidException) {
               throw new FailedException('token 不合法', Code::LOST_LOGIN);
           }

           throw new FailedException('登录用户不合法', Code::LOST_LOGIN);
       }

       return $next($request);
    }
}
