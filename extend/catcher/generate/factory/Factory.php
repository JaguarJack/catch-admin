<?php
namespace JaguarJack\Generator\Factory;

use catcher\CatchAdmin;

abstract class Factory
{
   abstract public function done($param);

    /**
     * parse psr4 path
     *
     * @time 2020年04月27日
     * @return mixed
     */
    public function parsePsr4()
    {
        $composer = \json_decode(file_get_contents(root_path() . 'composer.json'), true);

        return $composer['autoload']['psr-4'];
    }

    /**
     * get generate path
     *
     * @time 2020年04月27日
     * @param $filePath
     * @return string
     */
    protected function getGeneratePath($filePath)
    {
        $path = explode('\\', $filePath);

        $projectRootNamespace = array_shift($path);

        $filename = array_pop($path);

        $psr4 = $this->parsePsr4();

        $filePath = root_path() . $psr4[$projectRootNamespace.'\\'] . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $path);

        CatchAdmin::makeDirectory($filePath);

        return $filePath . DIRECTORY_SEPARATOR . $filename . '.php';
    }

    /**
     * 获取模块地址
     *
     * @time 2020年04月28日
     * @param $filePath
     * @return string
     */
    public function getModulePath($filePath)
    {
        $path = explode('\\', $filePath);

        $projectRootNamespace = array_shift($path);

        $module = array_shift($path);

        $psr4 = $this->parsePsr4();

        return root_path() . $psr4[$projectRootNamespace.'\\'] . DIRECTORY_SEPARATOR. $module . DIRECTORY_SEPARATOR;
    }
}