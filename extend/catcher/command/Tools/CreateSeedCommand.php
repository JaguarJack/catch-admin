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
use think\helper\Str;

class CreateSeedCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('create:seed')
            ->addArgument('table', Argument::REQUIRED, 'export tables')
            ->addOption('module', '-m', Option::VALUE_REQUIRED, 'module name')
            ->setDescription('Just for catchAdmin export data');
    }

    protected function execute(Input $input, Output $output)
    {
        $table = $input->getArgument('table');
        $module = $input->getOption('module');

        if ($module) {
            $data = Db::name($table)->where('deleted_at', 0)
                                //    ->where('module', $module)
                                    ->select()
                                    ->toArray();


        } else {
            $data = Db::name($table)->where('deleted_at', 0)
                                    ->select()
                                    ->toArray();
        }

        if ($module) {
            $data = var_export($data, true) . ';';
            $this->exportSeed($data,$table, $module);
        } else {
            file_put_contents(root_path() . DIRECTORY_SEPARATOR . $table . '.php', "<?php\r\n return " . var_export($data, true) . ';');
        }
        $output->info('succeed!');
    }

    protected function exportSeed($data,$table, $module)
    {
        $stub = file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'seed.stub');

        $model = Str::studly($table);
        $class = Str::studly($table) . 'Seed';

        $stub = str_replace('{CLASS}', $class, $stub);
        $stub = str_replace('{MODULE}', $module, $stub);
        $stub = str_replace('{MODEL}', $model, $stub);

        file_put_contents(CatchAdmin::moduleSeedsDirectory($module) . $class .'.php', str_replace('{DATA}', $data, $stub));
    }
}

