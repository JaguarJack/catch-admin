<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 11:38
 */
namespace thinking\icloud\exception;

class NotFoundException extends \Exception
{
    /**
     * key not found
     *
     * @time at 2019年01月26日
     * @param string $msg
     * @param int $code
     * @return NotFoundException
     */
    public static function NotFoundKey(string $msg, $code = 404)
    {
        return new static($msg, $code, null);
    }

    /**
     * method not found
     *
     * @time at 2019年01月26日
     * @param string $msg
     * @param int $code
     * @return NotFoundException
     */
    public static function NotFoundMethod(string $msg, int $code = 404)
    {
        return new static($msg, $code, null);
    }

    /**
     * extension not found
     *
     * @time at 2019年01月26日
     * @param string $msg
     * @param int $code
     * @return NotFoundException
     */
    public static function NotFoundExtension(string $msg, $code = 404)
    {
        return new static($msg, $code, null);
    }
}