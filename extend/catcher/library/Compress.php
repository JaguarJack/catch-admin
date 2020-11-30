<?php
declare(strict_types=1);

namespace catcher\library;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use catcher\facade\Http;
use function GuzzleHttp\Psr7\stream_for;
use catcher\facade\FileSystem;

class Compress
{
    protected $savePath;

    protected $zip;

    public function __construct()
    {
        if (!extension_loaded('zip')) {
            throw new FailedException('you should install extension [zip]');
        }
    }

    /**
     * 压缩模块包
     *
     * @time 2020年04月29日
     * @param $moduleName
     * @param string $zipPath
     * @return bool
     * @throws \Exception
     */
    public function moduleToZip(string $moduleName, string $zipPath = '')
    {
        if (!is_dir(CatchAdmin::directory() . $moduleName)) {
            throw new FailedException(sprintf('module 【%s】not found~', $moduleName));
        }

        (new Zip())->make($zipPath ? : CatchAdmin::directory() . $moduleName . '.zip', \ZipArchive::CREATE)
                   ->folder($moduleName)
                   ->addFiles(FileSystem::allFiles(CatchAdmin::moduleDirectory($moduleName)))
                   ->close();

        return true;
    }


    /**
     * download zip
     *
     * @time 2020年04月30日
     * @param $remotePackageUrl
     * @return string
     */
    public function download($remotePackageUrl = '')
    {
        $response = Http::options([
                       'save_to' => stream_for(fopen($this->savePath, 'w+'))
                    ])
                    ->get($remotePackageUrl);

       return $response->ok();
    }

    /**
     * 更新
     *
     * @time 2020年04月30日
     * @param $moduleName
     * @return bool
     */
    public function update($moduleName)
    {
        // 备份
        $backupPath = $this->backup($moduleName);
        try {
            $this->moduleUnzip($moduleName, $this->savePath);
        } catch (\Exception $exception) {
            // 更新失败先删除原目录
            FileSystem::deleteDirectory(CatchAdmin::moduleDirectory($moduleName));
            // 解压备份文件
            $this->moduleUnzip($moduleName, $backupPath);
            // 删除备份文件
            FileSystem::delete($backupPath);
            return false;
        }
        // 删除备份文件
        FileSystem::delete($backupPath);
        return true;
    }

    /**
     * overwrite package
     *
     * @time 2019年12月16日
     * @param $moduleName
     * @param $zipPath
     * @return bool
     * @throws \Exception
     */
    public function moduleUnzip($moduleName, $zipPath)
    {
        try {
            (new Zip())->make($zipPath)->extractTo(CatchAdmin::moduleDirectory($moduleName) . $moduleName)->close();
            return true;
        } catch (\Exception $e) {
            throw new FailedException('更新失败');
        }
    }


    /**
     * 删除目录
     *
     * @time 2020年04月29日
     * @param $packageDir
     * @return void
     */
    public function rmDir($packageDir)
    {
        $fileSystemIterator = new \FilesystemIterator($packageDir);
        try {
            foreach ($fileSystemIterator as $fileSystem) {
                if ($fileSystem->isDir()) {
                    if ((new \FilesystemIterator($fileSystem->getPathName()))->valid()) {
                        $this->rmDir($fileSystem->getPathName());
                    } else {
                        rmdir($fileSystem->getPathName());
                    }
                } else {
                    unlink($fileSystem->getPathName());
                }
            }
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }

        rmdir($packageDir);
    }

    /**
     *
     * @time 2020年04月29日
     * @param $path
     * @param string $moduleName
     * @param $tempExtractToPath
     * @return void
     */
    protected function copyFileToModule($path, $moduleName, $tempExtractToPath)
    {
        $fileSystemIterator = new \FilesystemIterator($path . $moduleName ? : '');

        foreach ($fileSystemIterator as $fileSystem) {
            if ($fileSystem->isDir()) {
                $this->copyFileToModule($fileSystem->getPathname(), '', $tempExtractToPath);
            } else {
                // 原模块文件
                $originModuleFile = str_replace($tempExtractToPath, CatchAdmin::directory(), $fileSystem->getPathname());
                // md5 校验 文件是否修改过
                if (md5_file($originModuleFile) != md5_file($fileSystem->getPathname())) {
                    if (!copy($fileSystem->getPathname(), $originModuleFile)) {
                       throw new FailedException('更新失败');
                    }
                }
            }
        }
    }

    /**
     * 备份原文件
     *
     * @time 2020年04月30日
     * @param $moduleName
     * @return bool
     */
    protected function backup($moduleName)
    {
        $backup = $this->getModuleBackupPath($moduleName);

        CatchAdmin::makeDirectory($backup);

        $this->moduleToZip($moduleName, $backup . $moduleName. '.zip');

        return $backup . $moduleName . '.zip';
    }

    /**
     * 获取备份地址
     *
     * @time 2020年04月30日
     * @param $moduleName
     * @return string
     */
    protected function getModuleBackupPath($moduleName)
    {
        return $backup = runtime_path('module' . DIRECTORY_SEPARATOR . 'backup_'.$moduleName);
    }

    /**
     * 保存地址
     *
     * @param $path
     * @return $this
     * @author JaguarJack <njphper@gmail.com>
     * @date 2020/7/11
     */
    public function savePath($path)
    {
        $this->savePath = $path;

        return $this;
    }
}