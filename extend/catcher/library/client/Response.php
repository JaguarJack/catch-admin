<?php
declare(strict_types=1);

namespace catcher\library\client;


use GuzzleHttp\Promise\Promise;

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
     * @var \GuzzleHttp\Psr7\Response|Promise
     */
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }


    /**
     *
     * @time 2020年05月22日
     * @return bool|callable|float|\GuzzleHttp\Psr7\PumpStream|\GuzzleHttp\Psr7\Stream|int|\Iterator|\Psr\Http\Message\StreamInterface|resource|string|null
     */
    public function body()
    {
        return $this->response->getBody();
    }

    /**
     * 响应内容
     *
     * @time 2020年05月22日
     * @return false|string
     */
    public function contents()
    {
        return $this->body()->getContents();
    }

    /**
     *
     * @time 2020年05月21日
     * @return array
     */
    public function json():array
    {
        return \json_decode($this->contents(), true);
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
     * 异步回调
     *
     * @time 2020年05月22日
     * @param callable $response
     * @param callable $exception
     * @return \GuzzleHttp\Promise\FulfilledPromise|Promise|\GuzzleHttp\Promise\PromiseInterface|\GuzzleHttp\Promise\RejectedPromise
     */
    public function then(callable $response, callable $exception)
    {
        return $this->response->then($response, $exception);
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
