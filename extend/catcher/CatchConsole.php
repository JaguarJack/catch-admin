<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catcher;

use catcher\library\Composer;
use catcher\facade\FileSystem;
use think\App;
use think\console\Command;

class CatchConsole
{
    protected $app;

    protected $namespace = '';

    protected $path = __DIR__ . DIRECTORY_SEPARATOR . 'command';

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * 获取 commands
     *
     * @time 2020年07月02日
     * @return array
     */
    public function commands()
    {
        $commandFiles = FileSystem::allFiles($this->path);

        $commands = [];

        /*  \Symfony\Component\Finder\SplFileInfo $command */
        foreach ($commandFiles as $command) {
            if ($command->getExtension() === 'php') {
                $lastPath = str_replace($this->parseNamespace(), '',pathinfo($command, PATHINFO_DIRNAME));
                $namespace = $this->namespace . str_replace(DIRECTORY_SEPARATOR, '\\', $lastPath) . '\\';
                $commandClass = $namespace . pathinfo($command, PATHINFO_FILENAME);
                $commands[] = $commandClass;
            }
        }

        return $commands;
    }

    /**
     * 命名空间解析
     *
     * @time 2020年07月19日
     * @return string
     */
    protected function parseNamespace()
    {
        // 没有设置 namespace 默认使用 extend 目录
        if (!$this->namespace) {
            return root_path(). 'extend';
        }

        $psr4 = (new Composer())->psr4Autoload();

        $rootNamespace = substr($this->namespace, 0, strpos($this->namespace, '\\') + 1);

        return root_path(). $psr4[$rootNamespace] . DIRECTORY_SEPARATOR .

                str_replace('\\', DIRECTORY_SEPARATOR, substr($this->namespace, strpos($this->namespace, '\\') + 1));
    }

    /**
     * set path
     *
     * @time 2020年07月02日
     * @param $path
     * @return $this
     */
    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * 设置命名空间
     *
     * @time 2020年07月02日
     * @param $namespace
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

}