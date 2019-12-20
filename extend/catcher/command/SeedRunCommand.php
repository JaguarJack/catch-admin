<?php
namespace catcher\command;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Argument as InputArgument;
use think\console\input\Option;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\migration\command\seed\Run;

class SeedRunCommand extends Run
{
    protected $module;

    protected function configure()
    {
        // 指令配置
        $this->setName('catch-seed:run')
            ->setDescription('the catch-seed:run command to Run database seeders')
            ->addArgument('module', Argument::REQUIRED, 'seed the module database')
            ->addOption('--seed', '-s', InputOption::VALUE_REQUIRED, 'What is the name of the seeder?')
            ->setHelp(<<<EOT
                The <info>catch-seed:run</info> command runs all available or individual seeders
<info>php think catch-seed:run module</info>
<info>php think catch-seed:run -s UserSeeder</info>
<info>php think catch-seed:run -v</info>

EOT
            );

    }

    protected function execute(Input $input, Output $output)
    {
        $this->module = strtolower($input->getArgument('module'));
        $seed = $input->getOption('seed');

        // run the seed(ers)
        $start = microtime(true);
        $this->seed($seed);
        $end = microtime(true);
        $this->seeds = null;
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');

    }

    /**
     *
     * 获取 seeder path
     * @return string
     * @param $module
     * @date: 2019/12/10 14:01
     */
    protected function getPath()
    {
        return CatchAdmin::moduleSeedsDirectory($this->module);
    }


}
