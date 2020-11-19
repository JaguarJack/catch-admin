<?php

namespace catcher\generate\build\classes;

use PhpParser\BuilderFactory;

class Uses
{
    public function name(string $name, string $as = '')
    {
        $build = (new BuilderFactory())->use($name);

        if ($as) {
            $build->as($as);
        }

        return $build;
    }

    public function function(string $function)
    {
        return (new BuilderFactory())->useFunction($function);
    }

    public function const(string $const)
    {
        return (new BuilderFactory())->useConst($const);
    }
}