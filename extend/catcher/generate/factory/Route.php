<?php
namespace JaguarJack\Generator\Factory;

use JaguarJack\Generator\Template\Content;

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
            $route[] = sprintf("\$router->resource('%s', '\%s')", $this->controllerName, $this->controller);
        }

        if (!empty($this->methods)) {
            foreach ($this->methods as $method) {
                $route[] = sprintf("\$router->%s('%s/%s', '\%s@%s')", $method[1], $this->controllerName, $method[0], $this->controller, $method[0] );
            }
        }

        return file_put_contents($this->getModulePath($this->controller) . DIRECTORY_SEPARATOR . 'route.php',
                                    $this->header() . implode(';'. PHP_EOL , $route) . ';');
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