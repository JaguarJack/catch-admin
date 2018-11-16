<?php

namespace app\http\middleware;

class checkLogin
{
    public function handle($request, \Closure $next)
    {
    	if (!$request->session('user')) {
    		return redirect(url('login'));
	    }

	    return $next($request);
    }
}
