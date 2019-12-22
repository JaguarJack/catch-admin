<?php

namespace catcher\command;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option as InputOption;
use think\console\Output;
use think\facade\Db;
use think\helper\Str;

class ModelGeneratorCommand extends Command
{
    protected function configure()
    {
        $this->setName('create:model')
            ->addArgument('model', Argument::REQUIRED, 'model name')
            ->addArgument('module', Argument::REQUIRED, 'module name')
            ->setDescription('create model');
    }

    protected function execute(Input $input, Output $output)
    {
        $model = ucfirst($input->getArgument('model'));
        $module = strtolower($input->getArgument('module'));

        $table = Str::snake($model);

        $modelFile= CatchAdmin::getModuleModelDirectory($module) . $model . '.php';

        file_put_contents($modelFile, $this->replaceContent([
            $module, $model, $table, $this->generateFields($this->getTableFields($table))
        ]));

        if (file_exists($modelFile)) {
            $output->info(sprintf('%s Create Successfully!', $modelFile));
        } else {
            $output->error(sprintf('%s Create Failed!', $modelFile));
        }
    }



    private function getTableFields($table): array
    {
        $fields = Db::query('show full columns from ' .
            config('database.connections.mysql.prefix') . $table);

        $new = [];

        foreach ($fields as $field) {
            $new[$field['Field']] = $field['Comment'];
        }

        return $new;
    }

    private function generateFields($fields)
    {
        $f = '';
        foreach ($fields as $field => $comment) {
            $f .= sprintf("'%s', // %s" . "\r\n\t\t\t", $field, $comment);
        }

        return $f;
    }

    private function replaceContent(array $replace)
    {
        return str_replace([
            '{Module}', '{Class}', '{Name}', '{Field}'
        ], $replace, $this->content());
    }

    private function content()
    {
        return <<<EOT
<?php
namespace catch\{Module}\model;

use cather\base\BaseModel;

class {Class} extends BaseModel
{
    protected \$name = '{Name}';
    
    protected \$field = [
            {Field}   
    ];  
}
EOT;
    }
}
