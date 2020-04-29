<?php
declare (strict_types = 1);

namespace catcher\command\Tools;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\input\Option as InputOption;
use think\console\Output;

class CompressPackageCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('package:zip')
             ->addArgument('package', Argument::REQUIRED, 'package name')
             ->addOption('compress', 'c', InputOption::VALUE_REQUIRED, 'zip or unzip')
             ->setDescription('compress package to zip');
    }

    protected function execute(Input $input, Output $output)
    {
       $package = $this->input->getArgument('package');
       $compress = $this->input->getOption('compress');

        if (!extension_loaded('zip')) {
            $this->output->error('you should install extension [zip]');
            exit;
        }

        if ($compress == 'false') {
            $this->unzip();
        } else {
            $this->zip($package);
        }
    }

    /**
     * 压缩包
     *
     * @time 2019年12月16日
     * @param $package
     * @return void
     */
    protected function zip($package): void
    {
        if (!is_dir(CatchAdmin::directory() . $package)) {
            $package =  $this->output->ask($this->input, sprintf('Can not find [%s] in catch directory, you should input package again', $package));
        }

        if (!is_dir(CatchAdmin::directory() . $package)) {
            $this->output->error('check package exists?');exit;
        }

        $files = $this->getFilesFromDir(CatchAdmin::directory() . $package);
        $packageZip = new \ZipArchive();
        $packageZip->open(CatchAdmin::directory() . $package . '.zip', \ZipArchive::CREATE);
        $packageZip->addEmptyDir($package);
        foreach ($files as $file) {
            $baseName = basename($file);
            $localName = str_replace([CatchAdmin::directory(), $baseName], ['', ''], $file);
            $packageZip->addFile($file, $localName . $baseName);
        }
        $packageZip->close();
        $this->output->info(sprintf('%s.zip compress successfully!', $package));
    }

    /**
     * overwrite package
     *
     * @time 2019年12月16日
     * @return bool
     */
    protected function unzip()
    {
        $zip = new \ZipArchive();

        // 创建解压包的临时目录
        $tempExtractToPath = runtime_path('module' . DIRECTORY_SEPARATOR . date('YmdHis'));
        CatchAdmin::makeDirectory($tempExtractToPath);

        /**$extractToPath = CatchAdmin::directory();

        if (is_dir($extractToPath)) {
            $this->rmDir($extractToPath);
        }*/

        $res = $zip->open(CatchAdmin::directory() . 'permissions.zip');

        if ($res === true) {
            $zip->extractTo($tempExtractToPath);
            $zip->close();
            $this->output->info('unzip successfully');
            $this->copyFileToModule($tempExtractToPath, 'permissions', $tempExtractToPath);
            $this->rmDir($tempExtractToPath);
            $this->output->info('更新成功');
            return true;
        }

        $this->output->error('unzip failed');
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
            $this->output->error($exception->getMessage());
            exit;
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
    protected function copyFileToModule($path, $moduleName = '', $tempExtractToPath)
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
                        $this->output->error($originModuleFile . ' 更新失败');
                    } else {
                        $this->output->error($originModuleFile . ' 更新成功');
                    }
                }
            }
        }
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
}
