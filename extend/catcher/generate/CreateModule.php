<?php
namespace catcher\generate;


use catcher\CatchAdmin;
use catcher\facade\FileSystem;
use catcher\library\Composer;

class CreateModule
{
    protected $module;

    protected $moduleDir;

    /**
     * @var string
     */
    protected $stubDir;
    /**
     * @var string
     */
    protected $namespaces;

    protected $name;

    protected $description;

    protected $dirs;

    protected $keywords;

    public function generate($params)
    {
        try {
            $this->module = $params['alias'];

            $this->name = $params['name'];

            $this->description = $params['description'] ?? '';

            $this->keywords = $params['keywords'] ?? '';

            $this->dirs = $params['dirs'];

            $this->init();

        } catch (\Exception $exception) {
             $this->rollback();
             dd($exception->getMessage());
        }
    }

    public function init()
    {
        $this->moduleDir = CatchAdmin::moduleDirectory($this->module);

        $this->stubDir =dirname(__DIR__) . DIRECTORY_SEPARATOR .
            'command'.DIRECTORY_SEPARATOR.
            'stubs' . DIRECTORY_SEPARATOR;

        $psr4 = (new Composer())->psr4Autoload();

        foreach ($psr4 as $namespace => $des) {
            if ($des === CatchAdmin::$root) {
                $this->namespaces = $namespace . $this->module . '\\';
                break;
            }
        }

        $this->createFile();
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

        $dirs = [];
        foreach (explode(',', $this->dirs) as $dir) {
            if ($dir == 'database') {
                $dirs[] = $this->moduleDir . 'database' . DIRECTORY_SEPARATOR . 'migrations';
                $dirs[] = $this->moduleDir . 'database' . DIRECTORY_SEPARATOR . 'seeds';
            } else {
                $dirs[] = $this->moduleDir . $dir;
            }
        }


        return $dirs;
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
        $service = FileSystem::sharedGet($this->stubDir . 'service.stub');

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
        $moduleJson = FileSystem::sharedGet( $this->stubDir . 'module.stub');

        $keywords = '';
        foreach (explode(',',$this->keywords) as $k) {
            $keywords .= "\"{$k}\",";
        }

        $content = str_replace(['{NAME}','{DESCRIPTION}','{MODULE}', '{KEYWORDS}','{SERVICE}'],
            [
                $this->name,
                $this->description,
                $this->module,
                trim($keywords, ','),
                '\\\\'. str_replace('\\', '\\\\',$this->namespaces . ucfirst($this->module) . 'Service')
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
        FileSystem::put($this->moduleDir . 'route.php', FileSystem::sharedGet($this->stubDir . 'route.stub'));
    }
}