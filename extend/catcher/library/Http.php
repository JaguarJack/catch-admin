<?php
namespace catcher\library;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class Http
{
    protected $auth = [];

    protected $proxy = [];

    /**
     * download
     *
     * @time 2020年04月30日
     * @param $remoteUrl
     * @param $filePath
     * @return mixed
     */
    public function download($remoteUrl, $filePath = null)
    {
        try {

            $params = [
                'timeout' => 5, // 请求超时时间
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


    public function proxy(array $proxy)
    {
        $this->proxy = $proxy;

        return $this;
    }
}
