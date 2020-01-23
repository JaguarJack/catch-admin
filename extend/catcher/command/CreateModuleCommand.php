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
            ->addOption('service', '-e',Option::VALUE_REQUIRED, 'service name')
            ->setDescription('create module service');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->module = strtolower($input->getArgument('module'));

        $this->moduleDir = CatchAdmin::moduleDirectory($this->module);
        $this->stubDir = __DIR__ . DIRECTORY_SEPARATOR .'stubs'. DIRECTORY_SEPARATOR;

        $composer = json_decode(file_get_contents($this->app->getRootPath() . 'composer.json'), true);

        $psr4 = $composer['autoload']['psr-4'];

        foreach ($psr4 as $namespace => $des) {
            if ($des === CatchAdmin::NAME) {
                $this->namespaces = $namespace . $this->module . '\\';
                break;
            }
        }
        $this->createController();
        $this->createService();
        $this->createMigration();
        $this->createSeeds();
        $this->createRoute();

        $output->warning('module created');
    }

  /**
   *
   * @time 2020年01月23日
   * @return void
   */
    protected function createController(): void
    {
        $controllers = $this->input->getOption('controller');

        $controllerPath = $this->moduleDir . 'controller' . DIRECTORY_SEPARATOR;

        if ($controllers) {
          CatchAdmin::makeDirectory($controllerPath);

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
    }

  /**
   *
   * @time 2020年01月23日
   * @param $controllers
   * @return void
   */
    protected function createRequest($controllers): void
    {
        $requestPath = $this->moduleDir .  'request' . DIRECTORY_SEPARATOR;

        CatchAdmin::makeDirectory($requestPath);

        $default = ['CreateRequest.php', 'UpdateRequest.php'];

        if (count($controllers) === 1) {
            CatchAdmin::makeDirectory($requestPath . ucwords($controllers[0]));
            foreach ($default as $v) {
                file_put_contents($requestPath . $controllers[0] . DIRECTORY_SEPARATOR . $v, str_replace(
                    ['{NAMESPACE}', '{CLASS}'],
                    [$this->namespaces . $controllers[0]. '\\request', 'Create'],
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
    }

  /**
   *
   * @time 2020年01月23日
   * @return void
   */
    protected function createMigration(): void
    {
        $migrations = $this->input->getOption('migration');

        $migrationPath = $this->moduleDir . 'database'.DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR;

        if ($migrations) {
            CatchAdmin::makeDirectory($migrationPath);
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

        if ($seeds) {
            CatchAdmin::makeDirectory($seedPath);

            $seeds = explode(',', $seeds);

            foreach ($seeds as $seed) {
                $filename = ucfirst(Str::camel($seed)) . 'Seed.php';

                file_put_contents($seedPath . $filename, str_replace(
                    ['{CLASS}'], [ucfirst($seed)], file_get_contents($this->stubDir.'seeder.stub')));
            }

            $this->output->info('🎉 create seeds successfully');
        }
    }

  /**
   *
   * @time 2020年01月23日
   * @param $service
   * @return void
   */
    protected function moduleJson($service): void
    {
      if (!file_exists($this->moduleDir.DIRECTORY_SEPARATOR .'module.json')) {
        file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . 'module.json', str_replace(
          ['{MODULE}', '{SERVICE}'],
          [$this->module, $service ? $this->namespaces . ucfirst($service) . 'Service' : ''],
          file_get_contents($this->stubDir . 'module.stub')));
        $this->output->info('🎉 create module.json successfully');
      }
    }

  /**
   *
   * @time 2020年01月23日
   * @return void
   */
    protected function createRoute(): void
    {
      if (!file_exists($this->moduleDir.DIRECTORY_SEPARATOR .'route.php')) {
        file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . 'route.php',
          file_get_contents($this->stubDir . 'route.stub'));
        $this->output->info('🎉 create route.php successfully');
      }
    }

  /**
   *
   * @time 2020年01月23日
   * @return void
   */
    protected function createService(): void
    {
        $service = $this->input->getOption('service');
        if ($service) {
            file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . ucfirst($this->module) . 'Service.php', str_replace(
                ['{CLASS}', '{NAMESPACE}'],
                [ucfirst($service), $this->namespaces . '\\' . $this->module],
                file_get_contents($this->stubDir.'service.stub')));
            $this->output->info('🎉 create service successfully');
        }

        $this->moduleJson($service);
    }


}
