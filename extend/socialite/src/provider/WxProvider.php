<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 11:18
 */
namespace thinking\socialite\provider;

use think\exception\HttpException;
use thinking\socialite\contract\Provider;
use thinking\socialite\User;

class WxProvider extends AbstractProvider implements Provider
{
    protected $authorizeUrl   = 'https://open.weixin.qq.com/connect/qrconnect';

    protected $accessTokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token';

    protected $openIdUrl      = 'https://graph.qq.com/oauth2.0/me';

    protected $userUrl        = 'https://api.weixin.qq.com/sns/userinfo';

    protected $clientIdKey    = 'appid';

    /**
     * 获取 Access Token
     *
     * @time at 2018年12月28日
     * @return mixed
     */
    protected function getAccessToken()
    {

        $response = $this->getHttpClient()->get($this->accessTokenUrl, [
            'verify' => false,
            'query'  => array_merge($this->getTokenParams(), ['grant_type' => 'authorization_code'])
        ]);

        parse_str($response->getBody()->getContents(), $token);

        if (!isset($token['access_token'])) {
            throw new HttpException(401, 'Access Token Missing, Please ReLogin');
        }
        return $token['access_token'];
    }

    /**
     * 获取 Open ID
     *
     * @time at 2018年12月28日
     * @return array
     */
    protected function getOpenId()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->getHttpClient()->get($this->openIdUrl, [
            'verify' => false,
            'query'   => ['access_token' => $accessToken]
        ]);

        $openidStr = (string)$response->getBody()->getContents();

        $openIdArr = json_decode(substr($openidStr,strpos($openidStr,'(')+1,-3),true);

        return array_merge($openIdArr, ['access_token' => $accessToken]);
    }

    /**
     * 获取用户信息
     *
     * @time at 2018年12月28日
     * @return mixed
     */
    public function user()
    {
        $getUserParams = $this->getOpenId();

        unset($getUserParams['app_id']);
        $getUserParams['oauth_consumer_key'] = $this->appId;

        $response = $this->getHttpClient()->get($this->userUrl, [
            'verify'  => false,
            'headers' => ['Accept' => 'application/json'],
            'query'   => $getUserParams,
        ]);

        $user = json_decode($response->getBody()->getContents(), true);

        $user['open_id'] = $getUserParams['openid'];

        return (new User)->setUser($user)->map([
            'id'       => $getUserParams['openid'],
            'nickname' => $user['nickname'],
            'avatar'   => $user['figureurl_2'],
        ]);

    }

}