<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 10:23
 */

namespace thinking\icloud\factory;

class ICloudFactory
{
    protected static $driver = null;

    /**
     * set driver
     *
     * @time at 2019年01月26日
     * @param string $driver
     * @return static
     */
    public static function driver(string $driver)
    {
        self::$driver = $driver;

        // 设置 config 驱动
        config('cloud.driver.default', $driver);

        return new static();
    }


    /**
     * 静态访问
     *
     * @time at 2019年01月26日
     * @param $name
     * @param mixed ...$arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        $cloud = !self::$driver ? config('cloud.driver.' . config('cloud.driver.default')) : config('cloud.driver.' . self::$driver);

        return (new $cloud)->$name(...$arguments);
    }

}