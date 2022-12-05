<?php

declare(strict_types=1);

namespace Catch\Support\Macros;

use Catch\Support\Tree;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as LaravelCollection;

class Collection
{
    /**
     * boot
     */
    public static function boot(): void
    {
        $collection = new static();

        $collection->toOptions();

        $collection->toTree();
    }

    /**
     * collection to tree
     *
     * @return void
     */
    public function toTree(): void
    {
        LaravelCollection::macro(__FUNCTION__, function (int $pid = 0, string $pidField = 'parent_id', string $child = 'children') {
            return Tree::done($this->all(), $pid, $pidField, $child);
        });
    }

    /**
     * toOptions
     *
     * @return void
     */
    public function toOptions(): void
    {
        LaravelCollection::macro(__FUNCTION__, function () {
            return $this->transform(function ($item, $key) use (&$options) {
                if ($item instanceof Arrayable) {
                    $item = $item->toArray();
                }

                if (is_array($item)) {
                    $item = array_values($item);
                    return [
                        'value' => $item[0],
                        'label' => $item[1]
                    ];
                } else {
                    return [
                        'value' => $key,
                        'label' => $item
                    ];
                }
            })->values();
        });
    }
}
