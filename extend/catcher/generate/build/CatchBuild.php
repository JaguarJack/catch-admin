<?php
namespace catcher\generate\build;

use catcher\generate\build\classes\Classes;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter\Standard;

class PHPBuild
{
    protected $astBuilder;

    public function __construct()
    {
        $this->astBuilder = app(BuilderFactory::class);
    }

    public function namespace(string $namespace)
    {
        $this->astBuilder = $this->astBuilder->namespace('god');

        return $this;
    }

    public function use($use)
    {
        $this->astBuilder->addStmt($use);

        return $this;
    }


    public function class(Classes $class, \Closure $function)
    {
        $function($class);

        $this->astBuilder->addStmt($class->build());


        return $this;
    }


    public function finish()
    {
        $stmts = array($this->astBuilder->getNode());
        $prettyPrinter = new Standard();
        dd($prettyPrinter->prettyPrintFile($stmts));
    }
}