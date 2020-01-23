<?php
namespace catcher\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use catcher\CatchAdmin;
use think\helper\Str;

class CreateModuleCommand extends Command
{
    protected $module;

    protected $moduleDir;

    protected $stubDir;

    protected $namespaces;

    protected function configure()
    {
        $this->setName('create:module')
            ->addArgument('module', Argument::REQUIRED,  'module name')
            ->addOption('controller', '-c',Option::VALUE_REQUIRED, 'controller name')
            ->addOption('migration', '-m',Option::VALUE_REQUIRED, 'migration name')
            ->addOption('seed', '-s',Option::VALUE_REQUIRED, 'seed name')
            ->addOption('service', '-se',Option::VALUE_REQUIRED, 'service name')
            ->setDescription('create module service');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->module = strtolower($input->getArgument('module'));


        $this->moduleDir = CatchAdmin::moduleDirectory($this->module);
        $this->stubDir = __DIR__ . DIRECTORY_SEPARATOR .'stubs'. DIRECTORY_SEPARATOR;

        $this->namespaces = CatchAdmin::NAME . '\\' . $this->module . '\\';

        $this->createController();
        $this->createService();
        $this->createMigration();
        $this->createSeeds();
        $this->createRoute();
        $this->moduleJson();

        $output->warning('module created');
    }


    protected function createController()
    {
        $controllers = $this->input->getOption('controller');

        $controllerPath = $this->moduleDir . 'controller' . DIRECTORY_SEPARATOR;

        CatchAdmin::makeDirectory($controllerPath);

        $controllers = $controllers ? explode(',', $controllers) : ['Index'];

        foreach ($controllers as $controller) {
            file_put_contents($controllerPath . ucfirst($controller) . '.php', str_replace(
                ['{CLASS}', '{NAMESPACE}', '{MODULE}'],
                [ucfirst($controller), $this->namespaces . 'controller', $this->module],
                file_get_contents($this->stubDir . 'controller.stub')
            ));
        }

        $this->output->info('🎉 create controller  successfully');
        $this->createRequest($controllers);
    }

    protected function createRequest($controllers)
    {
        $requestPath = $this->moduleDir . DIRECTORY_SEPARATOR . 'request' . DIRECTORY_SEPARATOR;

        CatchAdmin::makeDirectory($requestPath);

        $default = ['CreateRequest.php', 'UpdateRequest.php'];

        if (count($controllers) === 1) {
            foreach ($default as $v) {
                file_put_contents($requestPath . $v, str_replace(
                    ['{NAMESPACE}', '{CLASS}'],
                    [$this->namespaces . 'request', 'Create'],
                    file_get_contents($this->stubDir. 'request.stub')));
            }
        } else {
            foreach ($controllers as $controller) {
                CatchAdmin::makeDirectory($requestPath . ucwords($controller));
                foreach ($default as $v) {
                    file_put_contents($requestPath . ucwords($controller). DIRECTORY_SEPARATOR .  $v, str_replace(
                        ['{NAMESPACE}', '{CLASS}'],
                        [$this->namespaces . 'request' . ucwords($controller), 'Create'],
                        file_get_contents($this->stubDir . 'request.stub')));
                }
            }
        }
        $this->output->info('🎉 create view successfully');
    }

    protected function createMigration()
    {
        $migrations = $this->input->getOption('migration');

        $migrationPath = $this->moduleDir . 'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;

        CatchAdmin::makeDirectory($migrationPath);

        if ($migrations) {
            $migrations = explode(',', $migrations);

            foreach ($migrations as $migration) {
                $filename = date('Ymdhis', time()) . '_' .Str::snake($migration) . '.php';

                file_put_contents($migrationPath . $filename, str_replace(
                    ['{CLASS}'], [ucfirst($migration)], file_get_contents($this->stubDir.'migration.stub')));
            }
            $this->output->info('🎉 create migrations successfully');
        }
    }

    protected function createSeeds()
    {
        $seeds = $this->input->getOption('seed');

        $seedPath = $this->moduleDir . 'database'.DIRECTORY_SEPARATOR.'seeds'.DIRECTORY_SEPARATOR;

        CatchAdmin::makeDirectory($seedPath);

        if ($seeds) {
            $seeds = explode(',', $seeds);

            foreach ($seeds as $seed) {
                $filename = ucfirst(Str::camel($seed)) . 'Seed.php';

                file_put_contents($seedPath . $filename, str_replace(
                    ['{CLASS}'], [ucfirst($seed)], file_get_contents($this->stubDir.'seeder.stub')));
            }

            $this->output->info('🎉 create seeds successfully');
        }
    }

    protected function moduleJson()
    {
        file_put_contents($this->moduleDir.DIRECTORY_SEPARATOR .'module.json', str_replace(
            ['{MODULE}', '{SERVICE}'],
            [$this->module, $this->namespaces. ucfirst($this->module) . 'Service'],
            file_get_contents($this->stubDir . 'module.stub')));
        $this->output->info('🎉 create module.json successfully');
    }

    protected function createRoute()
    {
        file_put_contents($this->moduleDir.DIRECTORY_SEPARATOR .'route.php',
            file_get_contents($this->stubDir . 'route.stub'));
        $this->output->info('🎉 create route.php successfully');
    }

    protected function createService()
    {
        $service = $this->input->getOption('service');
        if ($service) {
            file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . ucfirst($this->module) . 'Service.php', str_replace(
                ['{CLASS}', '{NAMESPACE}'],
                [ucfirst($this->module), $this->namespaces . '\\' . $this->module],
                file_get_contents($this->stubDir.'service.stub')));
            $this->output->info('🎉 create service successfully');
        }
    }


}
