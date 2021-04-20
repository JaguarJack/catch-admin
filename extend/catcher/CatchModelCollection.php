<?php
declare(strict_types=1);

namespace catcher;

use catcher\library\excel\Excel;
use catcher\library\excel\ExcelContract;
use think\facade\Cache;
use think\helper\Str;
use think\model\Collection;

class CatchModelCollection extends Collection
{
    /**
     * tree 结构
     *
     * @time 2020年10月21日
     * @param int $pid
     * @param string $pidField
     * @param string $children
     * @return array
     */
    public function toTree($pid = 0, $pidField = 'parent_id', $children = 'children'): array
    {
        return Tree::done($this->toArray(), $pid, $pidField, $children);
    }


    /**
     * 导出数据
     *
     * @time 2020年10月21日
     * @param $header
     * @param string $path
     * @param string $disk
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @return mixed|string[]
     */
    public function export($header, $path = '', $disk = 'local'): array
    {
        $excel = new Class($header, $this->items) implements ExcelContract
        {
            protected $headers;

            protected $sheets;

            public function __construct($headers, $sheets)
            {
                $this->headers = $headers;

                $this->sheets = $sheets;
            }

            public function headers(): array
            {
                // TODO: Implement headers() method.
                return $this->headers;
            }

            public function sheets()
            {
                // TODO: Implement sheets() method.
                return $this->sheets;
            }
        };

        if (!$path) {
            $path = Utils::publicPath('exports');
        }

        return (new Excel)->save($excel, $path, $disk);
    }

    /**
     * 缓存 collection
     *
     * @time 2020年10月21日
     * @param $key
     * @param int $ttl
     * @param string $store
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function cache($key, int $ttl = 0, string $store = 'redis'): bool
    {
        return Cache::store($store)->set($key, $this->items, $ttl);
    }

    /**
     * 获取当前级别下的所有子级
     *
     * @time 2020年11月04日
     * @param array $ids
     * @param string $parentFields
     * @param string $column
     * @return array
     */
    public function getAllChildrenIds(array $ids, $parentFields = 'parent_id', $column = 'id'): array
    {
        array_walk($ids, function (&$item){
            $item = intval($item);
        });

        $childIds = $this->whereIn($parentFields, $ids)->column($column);

        if (!empty($childIds)) {
            $childIds = array_merge($childIds, $this->getAllChildrenIds($childIds));
        }

        return $childIds;
    }

    /**
     * implode
     *
     * @time 2021年02月24日
     * @param string $separator
     * @param string $column
     * @return string
     */
    public function implode(string $column = '', string $separator = ','): string
    {
        return implode($separator, $column ? array_column($this->items, $column) : $this->items);
    }
}