<?php
declare(strict_types=1);

namespace catcher;

class Tree
{
    public static function done(array $items, $pid = 0, $pidField = 'parent_id', $children = 'children')
    {
        $tree = [];

        foreach ($items as $key => $item) {
            if ($item[$pidField] == $pid) {
                $child = self::done($items, $item['id'], $pidField);
                if (count($child)) {
                  $item[$children] =  $child;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }


}
