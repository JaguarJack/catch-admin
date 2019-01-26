<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 11:18
 */
namespace thinking\socialite\provider;

use think\exception\HttpException;
use thinking\socialite\User;

class WeiBoProvider extends AbstractProvider
{
    protected $authorizeUrl   = 'https://api.weibo.com/oauth2/authorize';

    protected $accessTokenUrl = 'https://api.weibo.com/oauth2/access_token';

    protected $tokenInfoUrl   = 'https://api.weibo.com/oauth2/get_token_info';

    protected $userUrl        = 'https://api.weibo.com/2/users/show.json';

    /**
     * 获取 Access Token
     *
     * @time at 2018年12月28日
     * @return mixed
     */
    protected function getAccessToken()
    {

        $response = $this->getHttpClient()->post($this->accessTokenUrl, [
            'verify' => false,
            $this->getPostKey()  => array_merge($this->getTokenParams(), ['grant_type' => 'authorization_code'])
        ]);

        $token = json_decode($response->getBody()->getContents(), true);

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
    protected function getTokenInfo()
    {
        $accessToken = $this->getAccessToken();

        $response = $this->getHttpClient()->post($this->tokenInfoUrl, [
            'verify' => false,
            $this->getPostKey()   => ['access_token' => $accessToken]
        ]);

        $tokenInfo = json_decode($response->getBody()->getContents(), true);

        return ['access_token' => $accessToken, 'uid' => $tokenInfo['uid']];
    }
    
    /**
     * 获取用户信息
     *
     * @time at 2018年12月28日
     * @return mixed
     */
    public function user()
    {
        $response = $this->getHttpClient()->get($this->userUrl,[
            'verify' => false,
            'query'  => $this->getTokenInfo(),
        ]);

        $user = json_decode($response->getBody(), true);

        return (new User)->setUser($user)->map([
            'id'       => $user['idstr'],
            'nickname' => $user['name'],
            'avatar'   => $user['profile_image_url'],
        ]);
    }
}