<?php
declare (strict_types = 1);

namespace catcher\command;

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
            $package =  $this->output->ask($this->input, sprintf('Can not find [%s] in catchAdmin directory, you should input package again', $package));
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

        //dd($zip->open(CatchAdmin::directory() . 'permissions.zip'));
        $res = $zip->open(CatchAdmin::directory() . 'permissions.zip');
        $extractToPath = CatchAdmin::directory() . 'goods';

        if (is_dir($extractToPath)) {
            $this->rmDir($extractToPath);
        }

        $extractToPath = CatchAdmin::makeDirectory($extractToPath);

        if ($res === true) {
            $zip->extractTo($extractToPath);
            $zip->close();
            $this->output->info('unzip successfully');
            return true;
        }

        $this->output->error('unzip failed');
    }


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
