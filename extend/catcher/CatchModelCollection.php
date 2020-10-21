<?php
namespace catcher;

use catcher\library\excel\CatchExcel;
use catcher\library\excel\Excel;
use catcher\library\excel\ExcelContract;
use think\facade\Cache;
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
    public function toTree($pid = 0, $pidField = 'parent_id', $children = 'children')
    {
        return Tree::done($this->items, $pid, $pidField, $children);
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
    public function export($header, $path = '', $disk = 'local')
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
    public function cache($key, int $ttl = 0, string $store = 'redis')
    {
        return Cache::store($store)->set($key, $this->items, $ttl);
    }
}