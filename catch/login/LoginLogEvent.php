<?php
namespace catchAdmin\login;

use catchAdmin\permissions\model\Users;
use catchAdmin\system\model\LoginLog;
use think\facade\Db;

class LoginLogEvent
{
    public function handle($params)
    {
        $agent = request()->header('user-agent');

        app(LoginLog::class)->storeBy([
            'login_name' => $params['login_name'],
            'login_ip'   => request()->ip(),
            'browser'    => $this->getBrowser($agent),
            'os'         => $this->getOs($agent),
            'login_at'   => time(),
            'status'     => $params['success'],
        ]);
    }

    /**
     *
     * @time 2019年12月12日
     * @param $agent
     * @return string
     */
    private function getOs($agent): string
    {
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.1/i', $agent)) {
            return 'Windows 7';
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 6.2/i', $agent)) {
            return 'Windows 8';
        }
        if(false !== stripos($agent, 'win') && preg_match('/nt 10.0/i', $agent)) {
            return 'Windows 10';#添加win10判断
        }
        if (false !== stripos($agent, 'win') && preg_match('/nt 5.1/i', $agent)) {
            return 'Windows XP';
        }
        if (false !== stripos($agent, 'linux')) {
            return 'Linux';
        }
        if (false !== stripos($agent, 'mac')) {
            return 'mac';
        }

        return '未知';
    }

    /**
     *
     * @time 2019年12月12日
     * @param $agent
     * @return string
     */
    private function getBrowser($agent): string
    {
        if (false !== stripos($agent, "MSIE")) {
            return 'MSIE';
        }
        if (false !== stripos($agent, "Firefox")) {
            return 'Firefox';
        }
        if (false !== stripos($agent, "Chrome")) {
            return 'Chrome';
        }
        if (false !== stripos($agent, "Safari")) {
            return 'Safari';
        }
        if (false !== stripos($agent, "Opera")) {
            return 'Opera';
        }

        return '未知';
    }
}
