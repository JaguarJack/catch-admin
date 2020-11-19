<?php
namespace catcher\generate\factory;

use catcher\facade\FileSystem;
use catcher\generate\template\Content;

class Route extends Factory
{
    use Content;

    protected $controllerName;

    protected $controller;

    protected $restful;

    protected $methods = [];

    public function done($params = [])
    {
        $route = [];

        if ($this->restful) {
            $route[] = sprintf("\$router->resource('%s', '\%s');", $this->controllerName, $this->controller);
        }

        if (!empty($this->methods)) {
            foreach ($this->methods as $method) {
                $route[] = sprintf("\$router->%s('%s/%s', '\%s@%s');", $method[1], $this->controllerName, $method[0], $this->controller, $method[0] );
            }
        }

        $router = $this->getModulePath($this->controller) . DIRECTORY_SEPARATOR . 'route.php';

        $comment = '// ' . $this->controllerName . '路由';

        array_unshift($route, $comment);

        if (file_exists($router)) {
            return FileSystem::put($router, $this->parseRoute($router, $route));
        }

        return FileSystem::put($router, $this->header() . $comment. implode(';'. PHP_EOL , $route) . ';');
    }

    protected function parseRoute($path, $route)
    {
        $file = new \SplFileObject($path);
        // 保留所有行
        $lines = [];
        // 结尾之后的数据
        $down = [];
        // 结尾数据
        $end = '';
        while (!$file->eof()) {
            $lines[] = rtrim($file->current(), PHP_EOL);
            $file->next();
        }

        while (count($lines)) {
            $line = array_pop($lines);
            if (strpos($line, '})') !== false) {
                $end = $line;
                break;
            }
            array_unshift($down, $line);
        }

        $router = implode(PHP_EOL, $lines) . PHP_EOL;

        $routes = array_merge($down, $route);

        foreach ($routes as $r) {
            if ($r) {
                $router .= "\t" . $r . PHP_EOL;
            }
        }
        return $router .= $end;
    }

    /**
     * set class
     *
     * @time 2020年04月28日
     * @param $class
     * @return $this
     */
    public function controller($class)
    {
        $this->controller = $class;

        $class = explode('\\', $class);

        $this->controllerName = lcfirst(array_pop($class));

        return $this;
    }

    /**
     * set restful
     *
     * @time 2020年04月28日
     * @param $restful
     * @return $this
     */
    public function restful($restful)
    {
        $this->restful = $restful;

        return $this;
    }

    /**
     * set methods
     *
     * @time 2020年04月28日
     * @param $methods
     * @return $this
     */
    public function methods($methods)
    {
        $this->methods = $methods;

        return $this;
    }
}