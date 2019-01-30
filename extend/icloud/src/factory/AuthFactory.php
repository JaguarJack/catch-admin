<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 10:28
 */
namespace thinking\icloud\factory;

class AuthFactory
{
    /**
     * 认证驱动
     *
     * @time at 2019年01月26日
     * @param string $name
     * @param mixed ...$argument
     * @return mixed
     */
    public static function create(string $name, ...$argument)
    {
        $defaultDriver = config('cloud.driver.default');
        $auth = config('cloud.driver.' . $defaultDriver . 'Auth');
        return $auth::$name(...$argument);
    }

    /**
     * 静态访问
     *
     * @time at 2019年01月26日
     * @param string $name
     * @param $argument
     * @return mixed
     */
    public static function __callstatic(string $name, $argument)
    {
        return self::create($name, ...$argument);
    }
}