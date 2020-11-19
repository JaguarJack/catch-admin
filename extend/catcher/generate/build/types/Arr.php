<?php
namespace catcher\generate\build\types;

use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;

class Array_
{
    public function build($array)
    {
        $items = [];

        foreach ($array as $item) {
            $items[] = new ArrayItem(new String_($item));

        }

        return new \PhpParser\Node\Expr\Array_($items);
    }
}
