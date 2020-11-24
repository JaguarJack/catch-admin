<?php
namespace catcher\generate\build\types;

use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\Array_;

class Arr
{
    public function build($fields)
    {
        $items = [];

        foreach ($fields as $field) {
            $arrItem = new ArrayItem(new String_($field['name']));
            if ($field['comment']) {
                $arrItem->setDocComment(
                    new Doc('// ' . $field['comment'])
                );
            }
            $items[] = $arrItem;

        }

        return new Array_($items);
    }
}
