<?php

namespace catcher\generate\classes;

use think\helper\Str;

class Uses extends Iteration
{
    protected $uses;

    public function __construct($uses)
    {
        $delimiter = ' as ';

        foreach ($uses as $key => $use) {
            if (Str::contains($use, $delimiter)) {
                $uses[$key] = explode($delimiter, $use);
            }
        }

        $this->data = $uses;
    }
}