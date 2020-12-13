<?php
namespace catcher\command;

use catcher\facade\FileSystem;
use catcher\library\Composer;
use catcher\library\Compress;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use catcher\CatchAdmin;
use think\Exception;

class CreateModuleCommand extends Command
{
    protected $module;

    protected $moduleDir;

    protected $stubDir;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    protected $namespaces;

    protected function configure()
    {
        $this->setName('create:module')
            ->addArgument('module', Argument::REQUIRED,  'module name')
            ->setDescription('create module service');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            $this->module = strtolower($input->getArgument('module'));

            $this->name = $output->ask($input, '请输入模块中文名称');

            if (!$this->name) {
                while (true) {
                    $this->name = $output->ask($input, '请输入模块中文名称');
                    if ($this->name) {
                        break;
                    }
                }
            }

            $this->description = $output->ask($input, '请输入模块描述');

            $this->description = $this->description ? : '';

            $this->moduleDir = CatchAdmin::moduleDirectory($this->module);

            $this->stubDir = __DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR;

            $psr4 = (new Composer())->psr4Autoload();

            foreach ($psr4 as $namespace => $des) {
                if ($des === CatchAdmin::$root) {
                    $this->namespaces = $namespace . $this->module . '\\';
                    break;
                }
            }

            $this->createFile();
        } catch (\Exception $exception) {
            $this->rollback();
            $output->error($exception->getMessage());
            exit;
        }

        $output->info('module created');
    }

    /**
     * 创建失败 rollback
     *
     * @time 2020年06月25日
     * @return void
     */
    protected function rollback()
    {
        FileSystem::deleteDirectory($this->moduleDir);
    }

    /**
     * 模块文件夹
     *
     * @time 2020年06月25日
     * @return string[]
     */
    protected function modulePath()
    {
        return [
            $this->moduleDir . 'controller',
            $this->moduleDir . 'model',
            $this->moduleDir . 'database' . DIRECTORY_SEPARATOR . 'migrations',
            $this->moduleDir . 'database' . DIRECTORY_SEPARATOR . 'seeds',
        ];
    }

    /**
     * 模块文件
     *
     * @time 2020年06月25日
     * @return string[]
     */
    protected function moduleFiles()
    {
        return [
            $this->moduleDir . ucfirst($this->module). 'Service.php',
            $this->moduleDir . 'module.json',
            $this->moduleDir . 'route.php',
        ];
    }

    /**
     * 创建路径
     *
     * @time 2020年06月25日
     * @return void
     */
    protected function createDir()
    {
        foreach ($this->modulePath() as $path)
        {
            CatchAdmin::makeDirectory($path);
        }
    }

    /**
     * 创建文件
     *
     * @time 2020年06月25日
     * @return void
     */
    protected function createFile()
    {
        $this->createDir();
        $this->createService();
        $this->createRoute();
        $this->createModuleJson();
    }

    /**
     * 创建 service
     *
     * @time 2020年06月25日
     * @return void
     */
    protected function createService()
    {
        $service = FileSystem::sharedGet(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'service.stub');

        $content = str_replace(['{NAMESPACE}', '{SERVICE}'],
            [substr($this->namespaces, 0, -1),
                ucfirst($this->module) . 'Service'], $service);

        FileSystem::put($this->moduleDir . ucfirst($this->module) . 'Service.php', $content);
    }

    /**
     * 创建 module.json
     *
     * @time 2020年06月25日
     * @return void
     */
    protected function createModuleJson()
    {
        $moduleJson = FileSystem::sharedGet(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'module.stub');

        $content = str_replace(['{NAME}','{DESCRIPTION}','{MODULE}', '{SERVICE}', '{KEYWORDS}'],
            [
                $this->name, $this->description,
                $this->module,
                '\\\\'. str_replace('\\', '\\\\',
                $this->namespaces . ucfirst($this->module) . 'Service'),
                ''
            ], $moduleJson);

        FileSystem::put($this->moduleDir . 'module.json', $content);
    }

    /**
     * 创建路由文件
     *
     * @time 2020年06月25日
     * @return void
     */
    protected function createRoute()
    {
        FileSystem::put($this->moduleDir . 'route.php', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'route.stub'));
    }
}
