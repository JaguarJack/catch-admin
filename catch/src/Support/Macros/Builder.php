<?php

declare(strict_types=1);

namespace Catch\Support\Macros;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as LaravelBuilder;

class Builder
{
    /**
     * boot
     */
    public static function boot(): void
    {
        $builder = new static();

        $builder->whereLike();

        $builder->quickSearch();

        $builder->tree();
    }

    /**
     * where like
     *
     * @return void
     */
    public function whereLike(): void
    {
        LaravelBuilder::macro(__FUNCTION__, function ($filed, $value) {
            return $this->where($filed, 'like', "%$value%");
        });
    }


    /**
     * quick search
     *
     * @return void
     */
    public function quickSearch(): void
    {
        LaravelBuilder::macro(__FUNCTION__, function (array $params = []) {
            $params = array_merge(request()->all(), $params);

            if (! property_exists($this->model, 'searchable')) {
                return $this;
            }

            // filter null & empty string
            $params = array_filter($params, function ($value) {
                return (is_string($value) && strlen($value)) || is_numeric($value);
            });

            $wheres = [];

            if (! empty($this->model->searchable)) {
                foreach ($this->model->searchable as $field => $op) {
                    // 临时变量
                    $_field = $field;
                    // contains alias
                    if (str_contains($field, '.')) {
                        [, $_field] = explode('.', $field);
                    }

                    if (isset($params[$_field])) {
                        $opString = Str::of($op)->lower();
                        if ($opString->exactly('op')) {
                            $value = implode(',', $params[$_field]);
                        } elseif ($opString->exactly('like')) {
                            $value = "%{$params[$_field]}%";
                        } elseif ($opString->exactly('rlike')) {
                            $value = "{$params[$_field]}%";
                        } elseif ($opString->exactly('llike')) {
                            $value = "%{$params[$_field]}";
                        } else {
                            $value = $params[$_field];
                        }
                        $wheres[] = [$field, $op, $value];
                    }
                }
            }

            $this->where($wheres);

            return $this;
        });
    }

    /**
     * where like
     *
     * @time 2021年08月06日
     * @return void
     */
    public function tree(): void
    {
        LaravelBuilder::macro(__FUNCTION__, function (string $id, string $parentId, ...$fields) {
            $fields = array_merge([$id, $parentId], $fields);

            return $this->get($fields)->toTree(0, $parentId);
        });
    }
}
