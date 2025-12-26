<?php

namespace Modules\User\Listeners;

use Catch\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Modules\User\Events\Login as Event;
use Modules\User\Models\LogLogin;

class Login
{
    /**
     * Handle the event.
     */
    public function handle(Event $event): void
    {
        $request = $event->request;

        $this->log($request, (bool) $event->user, $event->token);

        if ($event->user) {
            $event->user->login_ip = $request->ip();
            $event->user->login_at = time();
            $event->user->remember_token = null;
            $event->user->save();
        }
    }

    /**
     * login log
     */
    protected function log(Request $request, int|bool $isSuccess, ?string $token): void
    {
        $tokenId = 0;
        if ($token) {
            [$tokenId] = explode('|', $token);
        }

        LogLogin::insert([
            'account' => $this->getAccount($request),
            'login_ip' => $request->ip(),
            'token_id' => $tokenId,
            'location' => $this->getLocation($request->ip()),
            'browser' => $this->getBrowserFrom(Str::of($request->userAgent())),
            'platform' => $this->getPlatformFrom(Str::of($request->userAgent())),
            'login_at' => time(),
            'status' => $isSuccess ? Status::Enable : Status::Disable,
        ]);
    }

    /**
     * get platform
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

    /**
     * @return mixed|string
     */
    protected function getAccount(Request $request)
    {
        if ($account = $request->get('account')) {
            return $account;
        }

        if ($mobile = $request->get('mobile')) {
            return $mobile;
        }

        return '未知';
    }

    /**
     * @param  string  $ip
     * @return string
     */
    public static function getLocation(string $ip): string
    {
        // 百度 IP 查询 API
        $url = "https://opendata.baidu.com/api.php?query=$ip&co=&resource_id=6006&oe=utf8";
        try {
            $response = Http::timeout(2)->get($url)->throw();

            if ($response['status']) {
                return '未知';
            }

            $location = $response['data'][0]['location'] ?? '未知';

            return Str::of($location)
                ->replace(['电信', '移动', '联通'], ['', '', ''])
                ->trim()
                ->toString();

        } catch (\Throwable $e) {
            return '未知';
        }
    }
}
