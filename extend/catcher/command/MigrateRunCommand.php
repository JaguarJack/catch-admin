<?php
namespace catcher\command;

use catcher\CatchAdmin;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\migration\command\migrate\Run;

class MigrateRunCommand extends Run
{
    protected $module;

    public function configure()
    {
        $this->setName('catch-migrate:run')
            ->setDescription('Migrate the database')
            ->addArgument('module', Argument::REQUIRED, 'migrate the module database')
            ->addOption('--target', '-t', InputOption::VALUE_REQUIRED, 'The version number to migrate to')
            ->addOption('--date', '-d', InputOption::VALUE_REQUIRED, 'The date to migrate to')
            ->setHelp(<<<EOT
The <info>migrate:run</info> command runs all available migrations, optionally up to a specific version

<info>php think catch-migrate:run module</info>
<info>php think catch-migrate:run module -t 20110103081132</info>
<info>php think catch-migrate:run module -d 20110103</info>
<info>php think catch-migrate:run -v</info>

EOT
            );
    }

    protected function execute(Input $input, Output $output)
    {
        $this->module = strtolower($input->getArgument('module'));
        $version = $input->getOption('target');
        $date    = $input->getOption('date');

        // run the migrations
        $start = microtime(true);
        if (null !== $date) {
            $this->migrateToDateTime(new \DateTime($date));
        } else {
            $this->migrate($version);
        }
        $end = microtime(true);

        // 重置 migrations 在循环冲无法重复使用
        $this->migrations = null;
        $output->writeln('');
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');
    }

    /**
     * 获取 migration path
     *
     * @time 2019年12月03日
     * @param $module
     * @return string
     */
    protected function getPath()
    {
        return CatchAdmin::moduleMigrationsDirectory($this->module);
    }
}
