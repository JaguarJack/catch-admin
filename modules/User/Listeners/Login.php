<?php

namespace Modules\User\Listeners;

use Catch\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Modules\User\Events\Login as Event;
use Modules\User\Models\LogLogin;
use Modules\User\Models\User;

class Login
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event): void
    {
        $request = $event->request;

        $this->log($request, (bool) $event->user);

        if ($event->user) {
            $event->user->login_ip = $request->ip();
            $event->user->login_at = time();
            $event->user->remember_token = null;
            $event->user->save();
        }
    }
    
    /**
     * login log
     *
     * @param Request $request
     * @param int $isSuccess
     * @return void
     */
    protected function log(Request $request, int $isSuccess): void
    {
        LogLogin::insert([
            'account' => $request->get('email'),
            'login_ip' => $request->ip(),
            'browser' => $this->getBrowserFrom(Str::of($request->userAgent())),
            'platform' => $this->getPlatformFrom(Str::of($request->userAgent())),
            'login_at' => time(),
            'status' => $isSuccess ? Status::Enable : Status::Disable
        ]);
    }


    /**
     * get platform
     *
     * @param Stringable $userAgent
     * @return string
     */
    protected function getBrowserFrom(Stringable $userAgent): string
    {
        return match (true) {
            $userAgent->contains('MSIE', true) => 'IE',
            $userAgent->contains('Firefox', true) => 'Firefox',
            $userAgent->contains('Chrome', true) => 'Chrome',
            $userAgent->contains('Opera', true) => 'Opera',
            $userAgent->contains('Safari', true) => 'Safari',
            default => 'unknown'
        };
    }


    /**
     * get os name
     *
     * @param Stringable $userAgent
     * @return string
     */
    protected function getPlatformFrom(Stringable $userAgent): string
    {
        return match (true) {
            $userAgent->contains('win', true) => 'Windows',
            $userAgent->contains('mac', true) => 'Mac OS',
            $userAgent->contains('linux', true) => 'Linux',
            $userAgent->contains('iphone', true) => 'iphone',
            $userAgent->contains('android', true) => 'Android',
            default => 'unknown'
        };
    }
}
