<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/28
 * Time: 11:50
 */
namespace thinking\socialite\provider;

use GuzzleHttp\Client;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\facade\Session;
use GuzzleHttp\ClientInterface;

class AbstractProvider
{
    // 应用ID
    protected $appId;
    // 应用秘钥
    protected $appSecret;
    // 回调地址
    protected $redirectUrl;
    // scope
    protected $scope;
    // http 客户端
    protected $httpClient = null;
    // request
    protected $request;

    protected $clientIdKey = 'client_id';

    public function __construct($appId = '', $appSecret='', $redirectUrl='', $scope = '')
    {
        $this->appId       = $appId;
        $this->appSecret   = $appSecret;
        $this->redirectUrl = $redirectUrl;
        $this->scope       = $scope;
        $this->request     = app('request');
    }

    protected function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client();
        }
        return $this->httpClient;
    }

    /**
     * oauth login
     *
     * @time at 2018年12月29日
     * @return void
     */
    public function oauth()
    {
        if (!$this->request->get('code')) {
            throw new HttpResponseException(redirect($this->authorizeUrl . '?' . http_build_query($this->createOauthParams())));
        }
    }

    /**
     * create oauth params
     *
     * @time at 2018年12月29日
     * @return array
     */
    protected function createOauthParams()
    {
        return [
            'response_type' => 'code',
            $this->clientIdKey => $this->appId,
            'redirect_uri'  => $this->redirectUrl,
             'scope'        =>  $this->getScope(),
            'state'         => $this->state(),
        ];
    }

    /**
     * set scope
     *
     * @time at 2018年12月29日
     * @param $scope
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * get scope
     *
     * @time at 2018年12月29日
     * @return string
     */
    protected function getScope()
    {
        return is_array($this->scope) ? trim(implode($this->scope), ',') : $this->scope;
    }

    /**
     * get state
     *
     * @time at 2018年12月29日
     * @return mixed
     */
    protected function getState()
    {
        $state = $this->request->session('state');

        $this->request->session('state', null);

        return $state;
    }

    /**
     * check state
     *
     * @time at 2018年12月29日
     * @return void
     */
    protected function checkState()
    {
        if ($this->request->param('state') != $this->getState()) {
            throw new HttpException(401, 'Authorized login State verification failed, Please check it');
        }
    }

    /**
     * generate state
     *
     * @time at 2018年12月29日
     * @return string
     */
    protected function state()
    {
        $state = md5(rand(1,100000));

        Session::set('state', $state);

        return $state;
    }

    /**
     * get token params
     *
     * @time at 2018年12月29日
     * @return array
     */
    protected function getTokenParams()
    {
        $this->checkState();

        return [
            'code'          => $this->request->get('code'),
            'client_secret' => $this->appSecret,
            $this->clientIdKey => $this->appId,
            'redirect_uri'  => $this->redirectUrl,
        ];
    }

    protected function getPostKey()
    {
        return (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';
    }
}