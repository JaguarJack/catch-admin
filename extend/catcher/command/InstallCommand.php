<?php
namespace catcher\command;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Console;
use think\facade\Db;

class InstallCommand extends Command
{

    protected $databaseLink = [];

    protected function configure()
    {
        $this->setName('catch:install')
            // ->addArgument('module', Argument::REQUIRED, 'module name')
            ->setDescription('install project');
    }

    /**
     *
     * @time 2019年11月29日
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     */
    protected function execute(Input $input, Output $output)
    {
        $this->detectionEnvironment();

        $this->firstStep();

        $this->secondStep();

        $this->thirdStep();

        $this->finished();

        $this->project();
    }

    /**
     * 环境检测
     *
     * @time 2019年11月29日
     * @return void
     */
    protected function detectionEnvironment(): void
    {
        $this->output->info('environment begin to check...');

        if (version_compare(PHP_VERSION, '7.1.0', '<')) {
            $this->output->error('php version should >= 7.1.0');
            exit();
        }

        $this->output->info('php version ' . PHP_VERSION);

        if (!extension_loaded('mbstring')) {
            $this->output->error('mbstring extension not install');exit();
        }
        $this->output->info('mbstring extension is installed');

        if (!extension_loaded('json')) {
            $this->output->error('json extension not install');
            exit();
        }
        $this->output->info('json extension is installed');

        if (!extension_loaded('openssl')) {
            $this->output->error('openssl extension not install');
            exit();
        }
        $this->output->info('openssl extension is installed');

        if (!extension_loaded('pdo')) {
            $this->output->error('pdo extension not install');
            exit();
        }
        $this->output->info('pdo extension is installed');

        if (!extension_loaded('xml')) {
            $this->output->error('xml extension not install');
            exit();
        }

        $this->output->info('xml extension is installed');

        $this->output->info('🎉 environment checking finished');
    }

    /**
     * 安装第一步
     *
     * @time 2019年11月29日
     * @return mixed
     */
    protected function firstStep()
    {
        if (file_exists($this->app->getRootPath() . '.env')) {
            return false;
        }

        $answer = strtolower($this->output->ask($this->input, '🤔️ Did You Need to Set Database information? (Y/N): '));

        if ($answer === 'y' || $answer === 'yes') {
            $charset = $this->output->ask($this->input, '👉 please input database charset, default (utf8mb4):') ? : 'utf8mb4';
            $database = '';
            while (!$database) {
                $database = $this->output->ask($this->input, '👉 please input database name: ');
                if ($database) {
                    break;
                }
            }
            $host = $this->output->ask($this->input, '👉 please input database host, default (127.0.0.1):') ? : '127.0.0.1';
            $port = $this->output->ask($this->input, '👉 please input database host port, default (3306):') ? : '3306';
            $prefix = $this->output->ask($this->input, '👉 please input table prefix, default (null):') ? : '';
            $username = $this->output->ask($this->input, '👉 please input database username default (root): ') ? : 'root';
            $password = '';
            while (!$password) {
                $password = $this->output->ask($this->input, '👉 please input database password: ');
                if ($password) {
                    break;
                }
            }

            $this->databaseLink = [$host, $database, $username, $password, $port, $charset, $prefix];

            $this->generateEnvFile($host, $database, $username, $password, $port, $charset, $prefix);
        }
    }

    /**
     * 安装第二部
     *
     * @time 2019年11月29日
     * @return void
     */
    protected function secondStep(): void
    {
        if (file_exists(root_path() . '.env')) {
            $connections = \config('database.connections');

            [
                $connections['mysql']['hostname'],
                $connections['mysql']['database'],
                $connections['mysql']['username'],
                $connections['mysql']['password'],
                $connections['mysql']['hostport'],
                $connections['mysql']['charset'],
                $connections['mysql']['prefix'],
            ] = $this->databaseLink;

            \config([
                'connections' => $connections,
            ], 'database');

            foreach (CatchAdmin::getModulesDirectory() as $directory) {
                $moduleInfo = CatchAdmin::getModuleInfo($directory);
                if (is_dir(CatchAdmin::moduleMigrationsDirectory($moduleInfo['alias']))) {
                    $output = Console::call('catch-migrate:run', [$moduleInfo['alias']]);
                    $this->output->info(sprintf('module [%s] migrations %s', $moduleInfo['alias'], $output->fetch()));

                    $seedOut = Console::call('catch-seed:run', [$moduleInfo['alias']]);
                    $this->output->info(sprintf('module [%s] seeds %s', $moduleInfo['alias'], $seedOut->fetch()));
                }
            }
        }
    }

    /**
     * 安装第四步
     *
     * @time 2019年11月29日
     * @return void
     */
    protected function thirdStep(): void
    {
        Console::call('catch:cache');
    }

    /**
     * finally
     *
     * @time 2019年11月30日
     * @return void
     */
    protected function finished(): void
    {
        // todo something
      // create jwt 
      Console::call('jwt:create');
    }

    /**
     * generate env file
     *
     * @time 2019年11月29日
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     * @param $port
     * @param $charset
     * @param $prefix
     * @return void
     */
    protected function generateEnvFile($host, $database, $username, $password, $port, $charset, $prefix): void
    {
        try {
            $env = \parse_ini_file(root_path() . '.example.env', true);

            $env['DATABASE']['HOSTNAME'] = $host;
            $env['DATABASE']['DATABASE'] = $database;
            $env['DATABASE']['USERNAME'] = $username;
            $env['DATABASE']['PASSWORD'] = $password;
            $env['DATABASE']['HOSTPORT'] = $port;
            $env['DATABASE']['CHARSET'] = $charset;
            if ($prefix) {
                $env['DATABASE']['PREFIX'] = $prefix;
            }
            $dotEnv = '';
            foreach ($env as $key => $e) {
                if (is_string($e)) {
                    $dotEnv .= sprintf('%s = %s', $key, $e === '1' ? 'true' : ($e === '' ? 'false' : $e)) . PHP_EOL;
                    $dotEnv .= PHP_EOL;
                } else {
                    $dotEnv .= sprintf('[%s]', $key) . PHP_EOL;
                    foreach ($e as $k => $v) {
                        $dotEnv .= sprintf('%s = %s', $k, $v === '1' ? 'true' : ($v === '' ? 'false' : $v)) . PHP_EOL;
                    }

                    $dotEnv .= PHP_EOL;
                }
            }


            if ($this->getEnvFile()) {
                $this->output->info('env file has been generated');
            }
            if ((new \mysqli($host, $username, $password, null, $port))->query(sprintf('CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARSET %s COLLATE %s_general_ci;',
                $database, $charset, $charset))) {
                $this->output->info(sprintf('🎉 create database %s successfully', $database));
            } else {
                $this->output->warning(sprintf('create database %s failed，you need create database first by yourself', $database));
            }
        } catch (\Exception $e) {
            $this->output->error($e->getMessage());
            exit(0);
        }

        file_put_contents(root_path() . '.env', $dotEnv);
    }

    /**
     *
     * @time 2019年11月29日
     * @return string
     */
    protected function getEnvFile(): string
    {
        return file_exists(root_path() . '.env') ? root_path() . '.env' : '';
    }

    /**
     * 检测根目录
     *
     * @time 2019年11月28日
     * @return bool
     */
    protected function checkRootDatabase(): bool
    {
        $databasePath = root_path('database');

        if (!is_dir($databasePath)) {
            if (!mkdir($databasePath, 0777, true) && !is_dir($databasePath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $databasePath));
            }
        }

        $migrationPath = $databasePath . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR;

        $seedPath = $databasePath . DIRECTORY_SEPARATOR . 'seeds' . DIRECTORY_SEPARATOR;

        if (!is_dir($migrationPath)) {
            if (!mkdir($migrationPath, 0777, true) && !is_dir($migrationPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $migrationPath));
            }
        }

        if (!is_dir($seedPath)) {
            if (!mkdir($seedPath, 0777, true) && !is_dir($seedPath)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $seedPath));
            }
        }

        return true;
    }


    protected function project()
    {
        $year = date('Y');

        $this->output->info('🎉 project is installed, welcome!');

        $this->output->info(sprintf('
 /-------------------- welcome to use -------------------------\                     
|               __       __       ___       __          _      |
|   _________ _/ /______/ /_     /   | ____/ /___ ___  (_)___  |
|  / ___/ __ `/ __/ ___/ __ \   / /| |/ __  / __ `__ \/ / __ \ |
| / /__/ /_/ / /_/ /__/ / / /  / ___ / /_/ / / / / / / / / / / |
| \___/\__,_/\__/\___/_/ /_/  /_/  |_\__,_/_/ /_/ /_/_/_/ /_/  |
|                                                              |   
 \ __ __ __ __ _ __ _ __ enjoy it ! _ __ __ __ __ __ __ ___ _ @ 2017 ～ %s
 账号: admin@gmail.com
 密码: admin                                               
', $year));

    }
}
