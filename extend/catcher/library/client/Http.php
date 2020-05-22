<?php
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

    protected $body = [];


    protected $header = [];


    protected $formParams = [];


    protected $query = [];


    protected $json = [];
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
     * Request get
     *
     * @time 2020年05月21日
     * @param $url
     * @return Response
     */
    public function get(string $url)
    {
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, array_merge($this->header, $this->query, $this->timeout)));
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
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, array_merge(
            $this->header, $this->body, $this->formParams, $this->json, $this->timeout, $this->multipart
        )));
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
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, array_merge(
            $this->header, $this->body, $this->formParams, $this->json, $this->timeout
        )));
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
        return new Response($this->getClient()->{$this->asyncMethod(__FUNCTION__)}($url, array_merge(
            $this->header, $this->query, $this->timeout
        )));
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

    public function onHeaders(\Closure $closure)
    {
        return $closure();
    }

    public function onStats(\Closure $closure)
    {
        return $closure();
    }


    /**
     * download
     *
     * @time 2020年04月30日
     * @param $remoteUrl
     * @param $filePath
     * @param int $timeout
     * @return mixed
     */
    public function download($remoteUrl, $filePath = null, $timeout = 5)
    {
        try {

            $params = [
                'timeout' => $timeout, // 请求超时时间
                'on_headers' => function (ResponseInterface $response) {
                    $response->getHeader('Content-Length');
                },
                'on_stats' => function (TransferStats $stats) {
                },
            ];

            if (!empty($this->auth)) {
                $params['auth'] = $this->auth;
            }

            if (!empty($proxy)) {
                $params['proxy'] = $this->proxy;
            }

            if ($filePath) {
                $resource = fopen($filePath, 'w+');

                $stream = stream_for($resource);

                $params['save_to'] = $stream;
            }

            return (new Client())->request('get', $remoteUrl, $params);
        } catch (\Exception $e) {
            throw new FailedException($e->getMessage());
        }

        return $filePath;
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
        $this->auth = [$username, $password];

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
