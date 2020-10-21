<?php
namespace catcher;

use think\model\Collection;

class CatchModelCollection extends Collection
{
    public function toTree($pid = 0, $pidField = 'parent_id', $children = 'children')
    {
        return Tree::done($this->items, $pid, $pidField, $children);
    }
}