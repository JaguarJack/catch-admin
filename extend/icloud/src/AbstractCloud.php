<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 11:37
 */
namespace thinking\icloud;

use thinking\icloud\factory\AuthFactory;
use thinking\icloud\httpclient\Client;
use thinking\icloud\exception\NotFoundException;
use thinking\icloud\Utility;

class AbstractCloud
{
    use Utility;

    protected $api;
    protected $host;
    protected $namespace;
    protected $response;

    public function __construct()
    {
        $this->host  = config('cloud.host');
    }


    /**
     * 获取 api url
     *
     * @time at 2019年01月26日
     * @param string $host
     * @param bool $isHttps
     * @throws NotFoundException
     * @return string
     */
    protected function host($host = 'rs', bool $isHttps = false)
    {
        if (!array_key_exists($host, $this->host)) {
            throw NotFoundException::NotFoundKey("Host Key '{$host}' Not Found In Config File");
        }
        return self::getHost($host, $isHttps);
    }

    /**
     * 指定目标资源空间与目标资源名编码
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $resourceName
     * @return mixed
     */
    protected function encodedEntry(string $bucket, string $resourceName)
    {
        return self::urlSafeBase64Encode(sprintf('%s:%s', $bucket, $resourceName));
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $client = new Client;
        $client->uri = $arguments[0];
        $client->method = $name;
        if (isset($arguments[1]['headers']['Authorization'])) {
            $client->params = $arguments[1];
        } else {
            $headers = AuthFactory::authorization($arguments[0], $name);
            $client->params = array_merge_recursive(['headers' => $headers], $arguments[1] ?? []);
        }
        return $client->send();
    }

    protected function send(string $uri, string $method, array $options = [])
    {
        $client         = new Client;
        $client->uri    = $uri;
        $client->method = $method;

        if (isset($options['headers']['Authorization'])) {
            $client->params = $options;
        } else {
            $headers = AuthFactory::authorization($uri, $method);
            $client->params = array_merge_recursive(['headers' => $headers], $options);
        }

        return $client->send();
    }

    /**
     * 上传凭证
     *
     * @time at 2019年01月26日
     * @param mixed ...$argument
     * @return mixed
     */
    public function uploadToken(...$argument)
    {
        return AuthFactory::uploadToken(...$argument);
    }
}