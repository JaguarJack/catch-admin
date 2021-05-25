<?php
declare(strict_types=1);

namespace catcher;

class Tree
{
    protected static $pk = 'id';

    /**
     *
     * @author CatchAdmin
     * @time 2021年05月25日
     * @param array $items
     * @param int $pid
     * @param string $pidField
     * @param string $children
     * @return array
     */
    public static function done(array $items, $pid = 0, $pidField = 'parent_id', $children = 'children'): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item[$pidField] == $pid) {
                $child = self::done($items, $item[self::$pk], $pidField);
                if (count($child)) {
                  $item[$children] =  $child;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * set pk field
     *
     * @author CatchAdmin
     * @time 2021年05月25日
     * @param string $pk
     * @return $this
     */
    public static function setPk(string $pk): Tree
    {
        self::$pk = $pk;

        return new self;
    }

}
