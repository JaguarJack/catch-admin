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
namespace catcher\library;

use catcher\facade\FileSystem;

class Zip
{
    protected $zipArchive;

    const EXTENSION = 'zip';

    protected $folder;

    public function __construct()
    {
        $this->zipArchive = new \ZipArchive();
    }

    /**
     * zip 文件
     *
     * @time 2020年07月19日
     * @param $zip
     * @param null $flags
     * @return $this
     * @throws \Exception
     */
    public function make($zip, $flags = null)
    {
        if (FileSystem::extension($zip) != self::EXTENSION) {
            throw new \Exception("make zip muse set [zip] extension");
        }

        $this->zipArchive->open($zip, $flags);

        return $this;
    }

    /**
     * 添加文件
     *
     * @time 2020年07月19日
     * @param $files
     * @param bool $relative
     * @return $this
     */
    public function addFiles($files, $relative = true)
    {
        if ($relative) {
            foreach ($files as $file) {
                $this->zipArchive->addFile($file->getPathname(), $this->folder . $file->getRelativePathname());
            }

        } else {
            foreach ($files as $file) {
                $this->zipArchive->addFile($file->getPathname(), $this->folder . $file->getPathname());
            }
        }

        return $this;
    }

    /**
     * 根目录
     *
     * @time 2020年07月19日
     * @param string $folder
     * @return $this
     */
    public function folder(string $folder)
    {
        $this->zipArchive->addEmptyDir($folder);

        $this->folder = $folder . DIRECTORY_SEPARATOR;

        return $this;
    }

    /**
     * 解压到
     *
     * @time 2020年07月19日
     * @param $path
     * @return $this
     * @throws \Exception
     */
    public function extractTo($path)
    {
        if (!$this->zipArchive->extractTo($path)) {
            throw new \Exception('extract failed');
        }

        return $this;
    }

    public function getFiles()
    {
        $this->zipArchive;
    }
    /**
     * 关闭
     *
     * @time 2020年07月19日
     * @return void
     */
    public function close()
    {
        if ($this->zipArchive) {
            $this->zipArchive->close();
        }
    }

}