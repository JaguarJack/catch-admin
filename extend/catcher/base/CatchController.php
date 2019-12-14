<?php
namespace catcher\base;

use catcher\CatchAdmin;
use think\facade\View;

abstract class CatchController
{
    protected $middleware = ['check_auth'];

    protected $data = [];

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

        if (!empty($this->data)) {
            $data = array_merge($this->data, $data);
        }

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

        $className = lcfirst(end($class));

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
        $this->data[$name] = $value;
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
