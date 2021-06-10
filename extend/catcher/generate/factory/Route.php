<?php
namespace catcher\generate\factory;

use catcher\facade\FileSystem;
use JaguarJack\Generate\Build\Value;
use JaguarJack\Generate\Define;
use PhpParser\ParserFactory;
use JaguarJack\Generate\Generator;

class Route extends Factory
{
    use HeaderDoc;

    protected $controllerName;

    protected $controller;

    protected $restful;

    protected $module;

    protected $methods = [];

    const VARIABLE_ST = 'scapegoat';

    /**
     * 实现
     *
     * @time 2021年06月09日
     * @param array $params
     * @throws \Exception
     * @return mixed
     */
    public function done(array $params = [])
    {
        $router = $this->getModulePath($this->controller) . DIRECTORY_SEPARATOR . 'route.php';

        $content = $this->generateRoute($router);

        $content = '<?php' . PHP_EOL .
            trim(str_replace(['$scapegoat ='], [''], $content), ';') . ';';

        if (! file_exists($router)) {
            return FileSystem::put($router, $content);
        }

        return FileSystem::put($router, $content);
    }


    /**
     * parse router methods
     *
     * @time 2021年06月09日
     * @return array
     * @throws \JaguarJack\Generate\Exceptions\TypeNotFoundException
     */
    protected function parseRouteMethods(): array
    {
        $generate = new Generator();

        $stmts = [];

        if ($this->restful) {
            $stmts[] = Define::variable(self::VARIABLE_ST, $generate->methodCall(['router', 'resource'], [
                Value::fetch($this->controllerName),
                Value::fetch(Define::classConstFetch($this->controller))
            ]), sprintf('// %s 路由', $this->controllerName));
        }

        if (!empty($this->methods)) {
            foreach ($this->methods as $method) {
                $stmts[] = Define::variable(self::VARIABLE_ST,
                    $generate->methodCall(['router', $method[1]], [
                    Value::fetch(sprintf('%s/%s',  $this->controllerName, $method[0])),
                    Value::fetch(sprintf('%s@%s',  $this->controller, $method[0]))
                ]));
            }
        }

        return $stmts;
    }

    /**
     * generate route
     *
     * @time 2021年06月09日
     * @param string $router
     * @return string
     * @throws \Exception
     */
    protected function generateRoute(string $router): string
    {
        $generate = new Generator();

        if (! FileSystem::exists($router)) {
            $stmts = $this->parseRouteMethods();

            $expr = Define::variable(self::VARIABLE_ST, $generate->call(
                            $generate->methodCall(['router', 'group'], [
                                Value::fetch($this->module),
                                $generate->closure()->uses('router')->body($stmts)
                             ]))
                        ->call('middleware', [Value::fetch('auth')])
                        ->call(),   $this->header() . PHP_EOL . '/* @var \think\Route $router */');

            return $generate->getContent([$expr]);
        } else {
            $factory = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

            $ast = $factory->parse(file_get_contents($router));

            $expression = $ast[0];

            $argKey = count($expression->expr->var->args) == 1 ? 0 : 1;

            $stmts = $expression->expr->var->args[$argKey]->value->stmts;

            $stmts = array_merge($stmts, $this->parseRouteMethods());

            $expression->expr->var->args[$argKey]->value->stmts = $stmts;

            $ast[0] = $expression;

            return $generate->getContent($ast);
        }
    }

    /**
     * set class
     *
     * @time 2020年04月28日
     * @param $class
     * @return $this
     */
    public function controller($class): Route
    {
        $this->controller = $class;

        $class = explode('\\', $class);

        $this->controllerName = lcfirst(array_pop($class));

        array_pop($class);

        $this->module = array_pop($class);

        return $this;
    }

    /**
     * set restful
     *
     * @time 2020年04月28日
     * @param $restful
     * @return $this
     */
    public function restful($restful): Route
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
    public function methods($methods): Route
    {
        $this->methods = $methods;

        return $this;
    }
}