<?php
namespace  app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\DB;

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
		$this->stubPath = $this->appPath . 'command' . DIRECTORY_SEPARATOR . 'stub' .DIRECTORY_SEPARATOR;
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
		$modelPath = $this->appPath  . DIRECTORY_SEPARATOR . 'model';
		if (!is_dir($modelPath)) {
			mkdir($modelPath, 0777, true);
		}
        $modelContents = "<?php \r\n \r\n";
		$modelContents .= "namespace app\model;\r\n \r\n";
		$modelContents .= 'class $modelModel extends BaseModel';
		$modelContents .= "\r\n { \r\n \t";
		$modelContents .= 'protected $table = \'' . config('database.prefix') . '$_table\';';
        $modelContents  = $this->writeField($modelContents, $modelName);
        $modelContents = str_replace('$model', ucfirst($modelName), $modelContents);
        $modelContents = str_replace('$_table', $this->unCamelize($modelName), $modelContents);
        $modelContents .= "\r\n }";

		return file_put_contents($modelPath . DIRECTORY_SEPARATOR . $modelName . 'Model.php', $modelContents);
	}

    private function writeField($modelContents, $modelName)
    {
        $info = Db::query('show full columns from ' . config('database.prefix') . $this->unCamelize($modelName));
        foreach ($info as $value) {
            $modelContents .= sprintf("\r\n %s \t protected $%s = '%s'; \r\n", $this->fieldComment($value['Comment']), $this->combine($value['Field']), $value['Field']);
        }

        return $modelContents;
    }
	// 创建模板
	public function makeView($controllerName, $moduleName)
	{
		$viewStub = $this->stubPath . 'View.stub';
		$viewPath   = (config('template.view_base') ?  config('template.view_base') . $moduleName . DIRECTORY_SEPARATOR : env('app_path') . $moduleName . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR) . strtolower($controllerName);
		if (!is_dir($viewPath)) {
			mkdir($viewPath, 0777, true);
		}

        $stub = explode('||', file_get_contents($viewStub));

        foreach ($this->views as $view) {
            if ($view == 'index') {
                file_put_contents($viewPath . DIRECTORY_SEPARATOR . $view .'.html', trim($stub[0]));
            } else {
                file_put_contents($viewPath . DIRECTORY_SEPARATOR . $view .'.html', trim($stub[1]));
            }
		}
	}

    /**
     * 字符注释
     *
     * @time at 2019年01月08日
     * @param $comment
     * @return string
     */
    private function fieldComment($comment)
    {
        return sprintf("\t /** \r\n \t  * @var string \r\n \t  * @desc %s \r\n \t  */ \r\n", $comment);
    }
    /**
     * 驼峰分割
     *
     * @time at 2019年01月02日
     * @param string $camelCaps
     * @param string $separator
     * @return string
     */
    private function unCamelize(string $string, string $separator = '_')
    {
        return strtolower(preg_replace('/(?<=[a-z])([A-Z])/', $separator . '$1', $string));
    }

    private function combine(string $string)
    {
        $s = explode('_', $string);
        array_walk($s, function (&$value, $key) {
            if ($key) {
                $value = ucfirst($value);
            }
        });
        return implode($s, '');
    }
}