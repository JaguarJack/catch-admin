<?php

namespace Modules\User\Services\Login;

use Illuminate\Support\Facades\Http;
use Modules\User\Models\User;

class Wechat implements LoginInterface
{
    protected string $getAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    protected string $getUserInfoURL = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * @param  array{wx_code: string}  $params
     */
    public function auth(array $params): User
    {
        // TODO: Implement auth() method.
        $wxUser = Http::get($this->getUserInfoURL, $this->getAccessTokenAndOpenId($params['wx_code']));

        // 因为微信平台的 unionid 都是一致的，所以先查询 unionid
        $user = User::where('unionid', $wxUser['unionid'])->first();

        // 如果之前其他微信其他平台的注册过，那么就同步用户信息
        if ($user) {
            // 如果已经是当前用户
            if ($user->wx_pc_openid == $wxUser['openid']) {
                return $user;
            }

            $user->wx_pc_openid = $wxUser['openid'];
            $user->save();

            return $user;
        } else {
            $user = new User();
            $user->wx_pc_openid = $wxUser['openid'];
            $user->username = $wxUser['nickname'];
            $user->avatar = $wxUser['headimgurl'];
            $user->unionid = $wxUser['unionid'];
            $user->save();

            return $user;
        }
    }

    protected function getAccessTokenAndOpenId(string $code): array
    {
        $response = Http::get($this->getAccessTokenURL, [
            'appid' => config('wechat.pc.app_id'),
            'secret' => config('wechat.pc.app_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);

        return [
            'openid' => $response['openid'],
            'access_token' => $response['access_token'],
        ];
    }
}
