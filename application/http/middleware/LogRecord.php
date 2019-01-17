<?php

namespace app\http\middleware;

use app\service\LogService;

class LogRecord
{

    public function handle($request, \Closure $next)
    {
        (new LogService())->record($request);

        return $next($request);
    }
}
