<?php
declare (strict_types = 1);

namespace catcher\command\Tools;

use catchAdmin\system\model\SensitiveWord;
use catcher\CatchAdmin;
use catcher\facade\FileSystem;
use catcher\library\Trie;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class CreateTableCommand extends Command
{
    protected $table;

    protected function configure()
    {
        // 指令配置
        $this->setName('create:table')
            ->addArgument('module', Argument::REQUIRED, 'module name')
            ->addArgument('table', Argument::REQUIRED, 'table name')
            ->addOption('form', '-f', Option::VALUE_NONE, '是否需要 form')
            ->setDescription('cache sensitive word');
    }

    protected function execute(Input $input, Output $output)
    {
        $module = $input->getArgument('module');
        $table = $input->getArgument('table');

        $form = $input->getOption('form');

        FileSystem::put(
            CatchAdmin::moduleDirectory($module) . 'tables' . DIRECTORY_SEPARATOR . (ucwords($table) . '.php'),
            $this->tableTemp($module, ucwords($table), $form)
        );

        if (! $form) {
            FileSystem::put(
                CatchAdmin::moduleDirectory($module) .
                'tables' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR
                . (ucwords($table) . '.php'),
                $this->formTemp($module, ucwords($table))
            );
        }

        $output->info('created success~');
    }


    protected function tableTemp($module, $table, $form)
    {
        $_table = lcfirst($table);

        $formTemp = ! $form ? sprintf('Factory::create(\'%s\');', $_table) : '[];';


        return <<<PHP
<?php
namespace catchAdmin\\{$module}\\tables;

use catcher\CatchTable;
use catchAdmin\\{$module}\\tables\\forms\\Factory;

class {$table} extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return \$this->getTable('{$_table}');
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return {$formTemp}
    }
    
}
PHP;

    }

    protected function formTemp($module, $table)
    {
            return <<<PHP
<?php
namespace catchAdmin\\{$module}\\tables\\forms;

use catcher\\library\\form\\Form;

class {$table} extends Form
{
    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            
        ];
    }
}
PHP;

    }
}
