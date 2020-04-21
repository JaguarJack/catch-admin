<?php
namespace catchAdmin\login;

use catchAdmin\permissions\model\Users;
use think\facade\Db;

class LoginLogEvent
{
    public function handle($params)
    {
        $agent = request()->header('user-agent');

        $username = Users::where('email', $params['email'])->value('username');

        Db::name('login_log')->insert([
            'login_name' => $username ? : $params['email'],
            'login_ip'   => request()->ip(),
            'browser'    => $this->getBrowser($agent),
            'os'         => $this->getOs($agent),
            'login_at'   => time(),
            'status'     => $params['success'] ? 1 : 2,
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
