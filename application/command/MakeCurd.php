<?php
namespace  app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class MakeCurd extends Command
{
	protected $appPath;
	protected $stubPath;
	// view 默认的三个模板
	protected $views = ['index', 'create', 'edit'];

	public function __construct()
	{
		parent::__construct();
		$this->appPath = env('app_path');
		$this->stubPath = $this->appPath . 'commands' . DIRECTORY_SEPARATOR . 'stub' .DIRECTORY_SEPARATOR;
	}

	protected function configure()
	{
		$this->setName('make:curd')
			 ->addArgument('controller', Argument::OPTIONAL, "controller name")
			 ->addArgument('model', Argument::OPTIONAL, "model name")
			 ->addOption('module', null, Option::VALUE_REQUIRED, 'module name')
			 ->setDescription('Create curd option controller model --module?');
	}

	protected function execute(Input $input, Output $output)
	{
		// 首先获取默认模块
		$moduleName = config('app.default_module');
		$controllerName = trim($input->getArgument('controller'));
		if (!$controllerName) {
			$output->writeln('Controller Name Must Set');exit;
		}

		$modelName = trim($input->getArgument('model'));

		if (!$modelName) {
			$output->writeln('Model Name Must Set');exit;
		}

		if ($input->hasOption('module')) {
			$moduleName = $input->getOption('module');
		}

		$this->makeController($controllerName, $moduleName);
		$output->writeln($controllerName . ' controller create success');
		$this->makeModel($modelName, $moduleName);
		$output->writeln($modelName . ' model create success');
		$this->makeView($controllerName, $moduleName);
		$output->writeln($controllerName . ' view create success');
	}
	// 创建控制器文件
	protected function makeController($controllerName, $moduleName)
	{
		$controllerStub = $this->stubPath  . 'Controller.stub';
		$controllerStub = str_replace(['$controller', '$module'], [ucfirst($controllerName), strtolower($moduleName)], file_get_contents($controllerStub));
		$controllerPath = $this->appPath . $moduleName . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR;
		if (!is_dir($controllerPath)) {
			mkdir($controllerPath, 0777, true);
		}
		return file_put_contents( $controllerPath . $controllerName . '.php', $controllerStub);
	}
	// 创建模型文件
	public function makeModel($modelName, $moduleName)
	{
		$modelStub = $this->stubPath . 'Model.stub';
		$modelPath = $this->appPath  . DIRECTORY_SEPARATOR . 'models';
		if (!is_dir($modelPath)) {
			mkdir($modelPath, 0777, true);
		}
		$modelStub = str_replace('$model', ucfirst($modelName), file_get_contents($modelStub));
		return file_put_contents($modelPath . DIRECTORY_SEPARATOR . $modelName . 'Model.php', $modelStub);
	}
	// 创建模板
	public function makeView($controllerName, $moduleName)
	{
		$viewStub = $this->stubPath . 'View.stub';
		$viewPath   = (config('template.view_base') ?  config('template.view_base') . $moduleName . DIRECTORY_SEPARATOR : env('app_path') . $moduleName . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR) . strtolower($controllerName);
		if (!is_dir($viewPath)) {
			mkdir($viewPath, 0777, true);
		}
		foreach ($this->views as $view) {
			file_put_contents($viewPath . DIRECTORY_SEPARATOR . $view .'.html', file_get_contents($viewStub));
		}
	}
}