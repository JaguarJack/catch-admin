<?php
namespace catcher\base;

use catcher\CatchAdmin;
use think\facade\View;

abstract class BaseController
{
    /**
     *
     * @time 2019年11月28日
     * @param array $data
     * @param string $template
     * @return string
     * @throws \Exception
     */
    protected function fetch(array $data = [], $template = ''): string
    {
        $stack = \debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        $end = end($stack);

        View::config([
            'view_path' => CatchAdmin::getViews()[$this->getModule($end['class'])]
        ]);

        return View::fetch($template ?  : $this->getTemp($end['class'], $end['function']), $data);

    }

    /**
     *
     * @time 2019年12月03日
     * @param $class
     * @param $func
     * @return string
     */
    protected function getTemp($class, $func)
    {
        $viewPath = CatchAdmin::getModuleViewPath($this->getModule($class));

        $class = explode('\\', $class);

        $className = strtolower(end($class));

        if (is_dir($viewPath . $className)) {
            return sprintf('%s/%s', $className, $func);
        }

        return $func;
    }

    /**
     *
     * @time 2019年12月03日
     * @param $class
     * @return mixed
     */
    protected function getModule($class)
    {
        return explode('\\', $class)[1];
    }
}
