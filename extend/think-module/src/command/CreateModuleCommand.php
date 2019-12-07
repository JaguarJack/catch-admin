<?php
namespace jaguarjack\think\module\command;

use catcher\CatchAdmin;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

class CreateModuleCommand extends Command
{
    protected $module;

    protected $moduleDir;

    protected $namespaces;

    protected function configure()
    {
        $this->setName('module:create')
            ->addArgument('module', Argument::REQUIRED,  'module name')
            ->setDescription('create module service');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->module = strtolower($input->getArgument('module'));

        $this->moduleDir = CatchAdmin::moduleDirectory($this->module);

        $this->namespaces = CatchAdmin::NAME . '\\\\' . $this->module . '\\\\';

        $this->createController();
        $this->createRequest();
        $this->createModel();
        // $this->createService();
        $this->createView();
        $this->createValidate();
        $this->createRoute();
        $this->moduleJson();

        $output->warning('module created');
    }


    protected function createController()
    {
        mkdir($this->moduleDir . 'controller' . DIRECTORY_SEPARATOR);
        return file_put_contents($this->moduleDir . 'controller' . DIRECTORY_SEPARATOR . 'Index.php', str_replace(
            ['{CLASS}', '{NAMESPACE}', '{MODULE}'],
            ['Index', $this->namespaces . 'controller', $this->module],
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .'stubs'.DIRECTORY_SEPARATOR. 'controller.stub')
        ));
    }


    protected function createModel()
    {

    }

    protected function createView()
    {
        mkdir($this->moduleDir . DIRECTORY_SEPARATOR . 'view');

        file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'index.html', '');
        file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'create.html', '');
        file_put_contents($this->moduleDir . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'edit.html', '');
    }

    protected function createValidate()
    {
        $validatePath = $this->moduleDir . DIRECTORY_SEPARATOR . 'validate' . DIRECTORY_SEPARATOR;
        mkdir($validatePath);
        file_put_contents($validatePath . 'CreateValidate.php', str_replace(
            ['{Namespace}', '{Class}'],
            [$this->namespaces . 'validate', 'Create'],
            file_get_contents(__DIR__ . 'stubs' . DIRECTORY_SEPARATOR . 'validate.stub')));

        file_put_contents($validatePath . 'UpdateValidate.php', str_replace(
            ['{Namespace}', '{Class}'],
            [$this->namespaces . 'validate', 'Update'],
            file_get_contents(__DIR__ . 'stubs' . DIRECTORY_SEPARATOR . 'validate.stub')));
    }

    protected function createRequest()
    {
        $requestPath = $this->moduleDir . DIRECTORY_SEPARATOR . 'request' . DIRECTORY_SEPARATOR;
        mkdir($requestPath);
        file_put_contents($validatePath . 'CreateRequest.php', str_replace(
            ['{Namespace}', '{Class}'],
            [$this->namespaces . 'request', 'Create'],
            file_get_contents(__DIR__ . 'stubs' . DIRECTORY_SEPARATOR . 'request.stub')));

        file_put_contents($validatePath . 'UpdateRequest.php', str_replace(
            ['{Namespace}', '{Class}'],
            [$this->namespaces . 'request', 'Update'],
            file_get_contents(__DIR__ . 'stubs' . DIRECTORY_SEPARATOR . 'request.stub')));
    }

    protected function database()
    {
        mkdir($this->moduleDir . DIRECTORY_SEPARATOR . 'database');
        mkdir($this->moduleDir . DIRECTORY_SEPARATOR . 'database'.DIRECTORY_SEPARATOR.'migrations');
        mkdir($this->moduleDir . DIRECTORY_SEPARATOR . 'database'.DIRECTORY_SEPARATOR . 'seeds');
    }

    protected function moduleJson()
    {
        file_put_contents($this->moduleDir.DIRECTORY_SEPARATOR .'module.json', str_replace(
            ['{MODULE}', '{SERVICE}'],
            [$this->module, $this->namespaces. ucfirst($this->module) . 'Service'],
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'module.stub')));
    }

    protected function createRoute()
    {
        file_put_contents($this->moduleDir.DIRECTORY_SEPARATOR .'route.php',
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'route.stub'));
    }

    protected function createService()
    {
        file_put_contents($this->moduleDir.DIRECTORY_SEPARATOR . ucfirst($this->module) . 'Service.php', str_replace(
            ['{CLASS}', '{NAMESPACE}'],
            [ucfirst($this->module), $this->namespaces . '\\' . $this->module],
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'provider.stub')));
    }


}
