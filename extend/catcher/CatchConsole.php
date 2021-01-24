<?php
declare(strict_types=1);

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
    public function commands(): array
    {
        $commandFiles = FileSystem::allFiles($this->path);

        $commands = [];

        /* \Symfony\Component\Finder\SplFileInfo $command */
        foreach ($commandFiles as $command) {
            if ($command->getExtension() === 'php') {
                $lastPath = str_replace($this->parseNamespace(), '', pathinfo($command->getPathname(), PATHINFO_DIRNAME));
                $namespace = $this->namespace . str_replace(DIRECTORY_SEPARATOR, '\\', $lastPath) . '\\';
                $commandClass = $namespace . pathinfo($command->getPathname(), PATHINFO_FILENAME);
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
    protected function parseNamespace(): string
    {
        $psr4 = (new Composer)->psr4Autoload();

        if (strpos($this->namespace, '\\') === false) {
            $rootNamespace = $this->namespace . '\\';
        } else {
            $rootNamespace = substr($this->namespace, 0, strpos($this->namespace, '\\') + 1);
        }

        $path = root_path(). $psr4[$rootNamespace] . DIRECTORY_SEPARATOR;

        if (strpos($this->namespace, '\\') !== false) {
            $path  .= str_replace('\\', DIRECTORY_SEPARATOR, substr($this->namespace, strpos($this->namespace, '\\') + 1));
        }

        return rtrim($path, '/');
    }

    /**
     * set path
     *
     * @time 2020年07月02日
     * @param $path
     * @return $this
     */
    public function path($path): CatchConsole
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
    public function setNamespace($namespace): CatchConsole
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * 默认 commands
     *
     * @time 2021年01月24日
     * @return array
     */
    public function defaultCommands(): array
    {
        $defaultCommands = FileSystem::allFiles(__DIR__ . DIRECTORY_SEPARATOR . 'command');

        $commands = [];

        /* \Symfony\Component\Finder\SplFileInfo $command */
        foreach ($defaultCommands as $command) {
            if ($command->getExtension() === 'php') {

                $filename = str_replace('.php', '', str_replace(__DIR__, '', $command->getPathname()));

                $class = 'catcher' . str_replace(DIRECTORY_SEPARATOR, '\\', $filename);

                if (class_exists($class)) {
                    $commands[] = $class;
                }
            }
        }

        return $commands;
    }

}