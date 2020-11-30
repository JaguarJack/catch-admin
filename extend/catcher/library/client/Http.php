<?php
declare(strict_types=1);

namespace catcher\library\client;

use catcher\exceptions\FailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class Http
{
    /**
     * @var Client $client
     */
    protected $client = null;

    /**
     * auth
     *
     * @var array
     */
    protected $auth = [];

    /**
     * 代理
     *
     * @var array
     */
    protected $proxy = [];

    /**
     * body
     *
     * @var array
     */
    protected $body = [];

    /**
     * header
     *
     * @var array
     */
    protected $header = [];

    /**
     * form params
     *
     * @var array
     */
    protected $formParams = [];

    /**
     * query set
     *
     * @var array
     */
    protected $query = [];

    /**
     * json set
     *
     * @var array
     */
    protected $json = [];

    /**
     *  可选参数
     * @var array
     */
    protected $options = [];

    /**
     * 异步请求
     *
     * @var bool
     */
    protected $async = false;


    /**
     * @var array
     */
    protected $timeout = [];

    /**
     * @var string
     */
    protected $token = '';

    protected $multipart = [];

    /**
     * 忽略证书
     *
     * @var array
     */
    protected $ignoreSsl = [];

    /**
     * 获取 Guzzle 客户端
     *
     * @time 2020年05月21日
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client;
        }

        return $this->client;
    }

    /**
     * headers
     *
     * @time 2020年05月21日
     * @param array $headers
     * @return $this
     */
    public function headers(array $headers)
    {

        $this->header = isset($this->header['headers']) ?
                            array_merge($this->header['headers'], $headers) :
                            [ 'headers' => $headers ];

        return $this;
    }

    /**
     * set bearer token
     *
     * @time 2020年05月22日
     * @param string $token
     * @return $this
     */
    public function token(string $token)
    {
        $this->header['headers']['authorization'] = 'Bearer '. $token;

        return $this;
    }

    /**
     * body
     *
     * @time 2020年05月21日
     * @param $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = [
            'body' => $body
        ];

        return $this;
    }

    /**
     * json
     *
     * @time 2020年05月21日
     * @param array $data
     * @return $this
     */
    public function json(array $data)
    {
        $this->json = [
            'json' => $data
        ];

        return $this;
    }

    /**
     * query
     *
     * @time 2020年05月21日
     * @param array $query
     * @return $this
     */
    public function query(array $query)
    {
        $this->query = [
            'query' => $query,
        ];

        return $this;
    }

    /**
     * form params
     *
     * @time 2020年05月21日
     * @param $params
     * @return $this
     */
    public function form(array $params)
    {
        $this->formParams = [
            'form_params' => array_merge($this->multipart, $params)
        ];

        return $this;
    }

    /**
     * timeout
     *
     * @time 2020年05月21日
     * @param $timeout
     * @return $this
     */
    public function timeout($timeout)
    {
        $this->timeout = [
            'connect_timeout' => $timeout
        ];

        return $this;
    }

    /**
     * 忽略 ssl 证书
     *
     * @return $this
     */
    public function ignoreSsl()
    {
        $this->ignoreSsl = [
            'verify' => false,
        ];

        return $this;
    }

    /**
     * 可选参数
     *
     * @time 2020年05月22日
     * @param array $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Request get
     *
     * @time 2020年05月21日
     * @param string $url
     * @return Response
     */
    public function get(string $url)
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }
    /**
     * Request post
     *
     * @time 2020年05月21日
     * @param $url
     * @return mixed
     */
    public function post(string $url)
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }

    /**
     * Request put
     *
     * @time 2020年05月21日
     * @param $url
     * @return mixed
     */
    public function put(string $url)
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }

    /**
     * Request delete
     *
     * @time 2020年05月21日
     * @param $url
     * @return mixed
     */
    public function delete(string $url)
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, $this->merge()));
    }


    /**
     * request params merge
     *
     * @time 2020年05月22日
     * @return array
     */
    protected function merge()
    {
        return array_merge($this->header, $this->query, $this->timeout,
            $this->options, $this->body, $this->auth, $this->multipart, $this->formParams,
            $this->ignoreSsl
        );
    }

    /**
     * 异步请求
     *
     * @time 2020年05月21日
     * @return $this
     */
    public function async()
    {
        $this->async = true;

        return $this;
    }

    /**
     * 附件
     *
     * @time 2020年05月22日
     * @param $name
     * @param $resource
     * @param $filename
     * @return $this
     */
    public function attach(string $name, $resource, string $filename)
    {
        $this->multipart = [
            'multipart' => [
                [
                    'name' => $name,
                    'contents' => $resource,
                    'filename' => $filename,
                ]
            ]
        ];

        return $this;
    }

    /**
     * 异步方法
     *
     * @time 2020年05月21日
     * @param $method
     * @return string
     */
    protected function asyncMethod($method)
    {
        return $this->async ? $method . 'Async' : $method;
    }

    /**
     * onHeaders
     *
     * @time 2020年05月22日
     * @param callable $callable
     * @return mixed
     */
    public function onHeaders(callable $callable)
    {
        $this->options['on_headers'] = $callable;

        return $this;
    }

    /**
     * onStats
     *
     * @time 2020年05月22日
     * @param callable $callable
     * @return mixed
     */
    public function onStats(callable $callable)
    {
        $this->options['on_stats'] = $callable;

        return $this;
    }

    /**
     * 认证
     *
     * @time 2020年04月30日
     * @param $username
     * @param $password
     * @return $this
     */
    public function auth($username, $password)
    {
        $this->options = [
            'auth' => $username, $password
        ];

        return $this;
    }

    /**
     * proxy
     *
     * @time 2020年05月21日
     * @param array $proxy
     * @return $this
     */
    public function proxy(array $proxy)
    {
        $this->proxy = $proxy;

        return $this;
    }
}
