<?php
declare (strict_types = 1);

namespace catcher\command;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class CompressPackageToZipCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('package:zip')
             ->addArgument('package', Argument::REQUIRED, 'package name')
             ->setDescription('compress package to zip');
    }

    protected function execute(Input $input, Output $output)
    {
       $package = $this->input->getArgument('package');
       if (!is_dir(CatchAdmin::directory() . $package)) {
           $package =  $this->output->ask($this->input, sprintf('Can not find [%s] in catchAdmin directory, you should input package again', $package));
       }

        if (!is_dir(CatchAdmin::directory() . $package)) {
            $this->output->error('check package exists?');exit;
        }
        $this->zip($package);

        // 指令输出
        $output->info('succeed!');
    }

    protected function zip($package)
    {
        $packageZip = new \ZipArchive($package . '.zip', \ZipArchive::CREATE);

        $files = $dirs = [];
        $this->getFilesFromDir(CatchAdmin::directory() . $package, $files, $dirs);

        dd($dirs);
        foreach ($files as $file) {
            var_dump(basename($file));
        }
        $packageZip->addFile();
        $packageZip->close();
    }

    protected function unzip()
    {

    }


    protected function getFilesFromDir($packageDir, &$files = [], &$dir = [])
    {
       $fileSystemIterator = new \FilesystemIterator($packageDir);

       foreach ($fileSystemIterator as $fileSystem) {
           if ($fileSystem->isDir()) {
               $this->getFilesFromDir($fileSystem->getPathName(), $files);
               $dir[] = $fileSystem->getPathName();
           } else {
               $files[] = $fileSystem->getPathName();
           }
       }
    }
}
