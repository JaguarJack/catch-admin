<?php
namespace catcher\library\client;


/**
 * http response
 *
 * From Laravel
 *
 * @time 2020年05月21日
 */
class Response implements \ArrayAccess
{
    /**
     * @var \GuzzleHttp\Psr7\Response
     */
    protected $response;

    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }

    /**
     *
     * @time 2020年05月21日
     * @return string
     */
    public function body():string
    {
        return $this->response->getBody();
    }

    /**
     *
     * @time 2020年05月21日
     * @return array
     */
    public function json():array
    {
        return \json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     *
     * @time 2020年05月21日
     * @return int
     */
    public function status():int
    {
        return $this->response->getStatusCode();
    }

    /**
     *
     * @time 2020年05月21日
     * @return bool
     */
    public function ok():bool
    {
        return $this->status() == 200;
    }

    /**
     *
     * @time 2020年05月21日
     * @return bool
     */
    public function successful():bool
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    /**
     *
     * @time 2020年05月21日
     * @return bool
     */
    public function failed():bool
    {
        return $this->status() >= 400;
    }

    /**
     *
     * @time 2020年05月21日
     * @return array
     */
    public function headers(): array
    {
        return $this->response->getHeaders();
    }

    /**
     *
     * @time 2020年05月21日
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this->response->{$name}(...$arguments);
    }

    /**
     *
     * @time 2020年05月21日
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
        return isset($this->json()[$offset]);
    }

    /**
     *
     * @time 2020年05月21日
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
        return $this->json()[$offset];
    }

    /**
     *
     * @time 2020年05月21日
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }
}
