<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 18:23
 */
namespace thinking\icloud\httpclient;

use GuzzleHttp\Client as HttpClient;
class Client implements \ArrayAccess
{
    protected $params = [];

    protected $client = null;

    protected $method = null;

    protected $uri    = null;

    /**
     *  发送数据
     *
     * @time at 2019年01月26日
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send()
    {
        return (new HttpClient)->request($this->method, $this->uri, $this->params);
    }

    public function set($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * 魔术方法
     *
     * @time at 2019年01月26日
     * @param string $key
     * @param $value
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this->$key = $value;
    }

    public function offsetSet($offset, $value)
    {
        $this->params[$offset] = $value;
    }

    public function offsetGet($offset)
    {
        return $this->params[$offset];
    }

    public function offsetExists($offset)
    {
        return isset($this->params[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->params[$offset]);
    }
}
