<?php
namespace catcher\library;

use catcher\CatchAdmin;
use catcher\exceptions\FailedException;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class Compress
{
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
     */
    public function moduleToZip($moduleName, $zipPath = '')
    {
        if (!is_dir(CatchAdmin::directory() . $moduleName)) {
            throw new FailedException(sprintf('module 【%s】not found~', $moduleName));
        }

        // 获取模块内的所有文件
        $files = $this->getFilesFromDir(CatchAdmin::directory() . $moduleName);

        $packageZip = new \ZipArchive();
        // zip 打包位置 默认打包在 catch 目录下
        $zipPath = $zipPath ? : CatchAdmin::directory() . $moduleName . '.zip';
        $packageZip->open($zipPath, \ZipArchive::CREATE);
        $packageZip->addEmptyDir($moduleName);
        foreach ($files as $file) {
            $baseName = basename($file);
            $localName = str_replace([CatchAdmin::directory(), $baseName], ['', ''], $file);
            $packageZip->addFile($file, $localName . $baseName);
        }
        $packageZip->close();

        return true;
    }

    /**
     * download zip
     *
     * @time 2020年04月30日
     * @param $moduleName
     * @return string
     */
    public function download($moduleName)
    {
        $client = new Client();

        $zip = CatchAdmin::directory() . $moduleName .'.zip';

        $resource = fopen($zip, 'w+');

        $stream = stream_for($resource);

        $client->request('get', 'http://api.catchadmin.com/permissions.zip', [
            'auth' => ['username', 'password'],
            'timeout' => 5, // 请求超时时间
            'on_headers' => function(ResponseInterface $response) {
                $response->getHeader('Content-Length');
            },
            'on_stats' => function(TransferStats $stats) {
                $size = $stats->getResponse()->getBody()->getSize();
                $time = $stats->getTransferTime();
                var_dump($size, $time);
            },
            'save_to' => $stream,
        ]);

        return $zip;
    }

    /**
     * 更新
     *
     * @time 2020年04月30日
     * @param $moduleName
     * @return void
     */
    public function update($moduleName)
    {
        $moduleZip = $this->download($moduleName);
        // 备份
        $backupPath = $this->backup($moduleName);
        try {
            $this->moduleUnzip($moduleName, $moduleZip);
        } catch (\Exception $exception) {
            $this->moduleUnzip($moduleName, $backupPath);
            $this->rmDir($this->getModuleBackupPath($moduleName));
        }
    }

    /**
     * overwrite package
     *
     * @time 2019年12月16日
     * @param $moduleName
     * @param $zipPath
     * @return bool
     */
    public function moduleUnzip($moduleName, $zipPath)
    {
        $zip = new \ZipArchive();

        // 创建解压包的临时目录
        $tempExtractToPath = runtime_path('module' . DIRECTORY_SEPARATOR . date('YmdHis'));
        CatchAdmin::makeDirectory($tempExtractToPath);
        // 下载 zip 包
        $res = $zip->open($zipPath);

        if ($res === true) {
            $zip->extractTo($tempExtractToPath);
            $zip->close();
            $this->copyFileToModule($tempExtractToPath, $moduleName, $tempExtractToPath);
            // 删除临时文件夹
            $this->rmDir($tempExtractToPath);
            return true;
        }

        throw new FailedException('更新失败');
    }

    /**
     * get files from dir
     *
     * @time 2019年12月16日
     * @param $packageDir
     * @return array
     */
    protected function getFilesFromDir($packageDir): array
    {
        $files = [];

        $fileSystemIterator = new \FilesystemIterator($packageDir);

        foreach ($fileSystemIterator as $fileSystem) {
            if ($fileSystem->isDir()) {
                $files = array_merge($this->getFilesFromDir($fileSystem->getPathName()), $files);
            } else {
                $files[] = $fileSystem->getPathName();
            }
        }

        return $files;
    }

    /**
     * 删除目录
     *
     * @time 2020年04月29日
     * @param $packageDir
     * @return void
     */
    protected function rmDir($packageDir)
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
}