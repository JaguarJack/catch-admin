<?php
namespace catcher\base;

use catcher\CatchAdmin;
use catcher\CatchResponse;
use think\facade\View;

abstract class CatchController
{
    public function __construct()
    {
        $this->loadConfig();
    }


    /**
     *
     *
     * @time 2019年12月15日
     * @return void
     */
    protected function loadConfig()
    {
        $module = explode('\\', get_class($this))[1];

        $moduleConfig = CatchAdmin::moduleDirectory($module) . 'config.php';

        if (file_exists(CatchAdmin::moduleDirectory($module) . 'config.php')) {
            app()->config->load($moduleConfig);
        }
    }

    /**
     *
     * @time 2019年12月13日
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
    }

    public function __isset($name)
    {
        // TODO: Implement __isset() method.
    }
}
