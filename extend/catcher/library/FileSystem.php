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
namespace catcher\library;

class FileSystem
{
    /**
     * 获取目录下所有文件
     *
     * @time 2020年07月02日
     * @param $path
     * @return array
     */
    public function allFiles($path)
    {
        $files = $this->files($path);

        $dirs = $this->dirs($path);

        foreach ($dirs as $dir) {
            $files = array_merge($files, $this->allFiles($dir));
        }

        return $files;
    }

    /**
     * 获取文件夹下的文件
     *
     * @time 2020年07月02日
     * @param $path
     * @return array
     */
    public function files($path)
    {
        $files = [];

        $fileSystemIterator = new \FilesystemIterator($path);

        foreach ($fileSystemIterator as $fileSystem) {
            if (!$fileSystem->isDir()) {
                $files[] = $fileSystem->getPathName();
            }
        }

        return $files;
    }

    /**
     * 获取文件夹
     *
     * @time 2020年07月02日
     * @param $path
     * @return array
     */
    public function dirs($path)
    {
        $dirs = [];

        $fileSystemIterator = new \FilesystemIterator($path);

        foreach ($fileSystemIterator as $fileSystem) {
            if ($fileSystem->isDir()) {
                $dirs[] = $fileSystem->getPathname();
            }
        }

        return $dirs;
    }
}