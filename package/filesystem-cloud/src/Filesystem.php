<?php
namespace jaguarjack\filesystem\cloud;

use think\exception\InvalidArgumentException;
use think\filesystem\Driver;
use think\helper\Arr;
use think\helper\Str;
use think\Manager;

class Filesystem extends Manager
{
    protected $namespace = __NAMESPACE__ . '\\drivers\\';

    /**
     * @param null|string $name
     * @return Driver
     */
    public function disk(string $name = null): Driver
    {
        return $this->driver($name);
    }

    protected function resolveType(string $name)
    {
        return $this->getDiskConfig($name, 'type', 'local');
    }

    protected function resolveConfig(string $name)
    {
        return $this->getDiskConfig($name);
    }

    /**
     * 获取缓存配置
     * @access public
     * @param null|string $name    名称
     * @param mixed       $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null)
    {
        if (!is_null($name)) {
            return $this->app->config->get('filesystem.' . $name, $default);
        }

        return $this->app->config->get('filesystem');
    }

    public function resolveClass(string $type): string
    {
        if ($this->namespace || false !== strpos($type, '\\')) {
            $class = false !== strpos($type, '\\') ? $type : $this->namespace . Str::studly($type) . 'Driver';

            if (class_exists($class)) {
                return $class;
            }
        }

        throw new InvalidArgumentException("Driver [$type] not supported.");
    }

    /**
     * 获取磁盘配置
     * @param string $disk
     * @param null   $name
     * @param null   $default
     * @return array
     */
    public function getDiskConfig($disk, $name = null, $default = null)
    {
        if ($config = $this->getConfig("disks.{$disk}")) {
            return Arr::get($config, $name, $default);
        }

        throw new InvalidArgumentException("Disk [$disk] not found.");
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }
}