<?php
declare (strict_types = 1);

namespace catcher\command\Tools;

use catcher\library\Compress;
use catcher\library\Http;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class CompressPackageCommand extends Command
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

        try {
            (new Compress())->moduleToZip($package);
        } catch (\Exception $e) {
            $output->error($e->getMessage());
        }

        $output->info($package . ' zip successfully~');
    }
}
