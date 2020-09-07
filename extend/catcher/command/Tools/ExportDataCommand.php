<?php
declare (strict_types = 1);

namespace catcher\command\Tools;

use catcher\CatchAdmin;
use catcher\facade\Http;
use catcher\Tree;
use catcher\Utils;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class ExportDataCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('export')
            ->addArgument('table', Argument::REQUIRED, 'export tables')
            ->addOption('pid', '-p', Option::VALUE_REQUIRED, 'parent level name')
            ->addOption('module', '-m', Option::VALUE_REQUIRED, 'module name')
            ->setDescription('Just for catchAdmin export data');
    }

    protected function execute(Input $input, Output $output)
    {
        //$table = // Utils::tablePrefix() .
        $table = $input->getArgument('table');
        $parent = $input->getOption('pid');
        $module = $input->getOption('module');

        if ($module) {
            $data = Db::name($table)->where('deleted_at', 0)
                                    ->where('module', $module)
                                    ->select()
                                    ->toArray();


        } else {
            $data = Db::name($table)->where('deleted_at', 0)
                                    ->select()
                                    ->toArray();
        }

        if ($parent) {
            $data = Tree::done($data, 0, $parent);
        }

        if ($module) {
            $data = 'return ' . var_export($data, true) . ';';
            $this->exportSeed($data, $module);
        } else {
            file_put_contents(root_path() . DIRECTORY_SEPARATOR . $table . '.php', "<?php\r\n return " . var_export($data, true) . ';');
        }
        $output->info('succeed!');
    }

    protected function exportSeed($data, $module)
    {
        $stub = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'permissionSeed.stub');

        $class = ucfirst($module) . 'MenusSeed';

        $stub = str_replace('{CLASS}', $class, $stub);

        file_put_contents(CatchAdmin::moduleSeedsDirectory($module) . $class .'.php', str_replace('{DATA}', $data, $stub));
    }
}

