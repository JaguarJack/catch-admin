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

class GithubProvider extends AbstractProvider
{
    protected $authorizeUrl   = 'https://github.com/login/oauth/authorize';

    protected $accessTokenUrl = 'https://github.com/login/oauth/access_token';

    protected $userUrl        = 'https://api.github.com/user';

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
            'headers' => ['Accept' => 'application/json'],
            $this->getPostKey()  => array_merge($this->getTokenParams())
        ]);

        $token = json_decode($response->getBody()->getContents(), true);

        if (!isset($token['access_token'])) {
            throw new HttpException(401, 'Access Token Missing, Please ReLogin');
        }

        return $token['access_token'];
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
            'headers' => ['Authorization' => sprintf('token %s', $this->getAccessToken())]
        ]);

        $user = json_decode($response->getBody(), true);

        return (new User)->setUser($user)->map([
            'id'       => $user['id'],
            'nickname' => $user['login'],
            'name'     => $user['name'],
            'email'    => $user['email'],
            'avatar'   => $user['avatar_url'],
        ]);
    }
}