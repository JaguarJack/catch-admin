<?php
declare(strict_types=1);

namespace catcher\library\excel;

trait MacroExcel
{
    /**
     * @var string
     */
    protected $start = 'A';

    /**
     * 开始行
     *
     * @var int
     */
    protected $row = 1;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * 设置开始的单元
     *
     * @time 2020年05月25日
     * @return string
     */
    protected function getStartSheet(): string
    {
        if (method_exists($this->excel, 'start')) {
            $this->start = $this->excel->start();
        }

        return $this->start;
    }

    /**
     * 设置单元格宽度
     *
     * @time 2020年05月25日
     * @return void
     */
    protected function setSheetWidth()
    {
        if (method_exists($this->excel, 'setWidth')) {
            $width = $this->excel->setWidth();

            foreach ($width as $sheet => $w) {
                $this->getWorksheet()->getColumnDimension($sheet)->setWidth($w);
            }
        }
    }

    /**
     * before
     *
     * @time 2020年05月25日
     * @return void
     */
    protected function before()
    {
        if (method_exists($this->excel, 'before')) {
            $this->excel->before();
        }
    }

    /**
     * 设置 column 信息 ['A', 'B', 'C' ...]
     *
     * @time 2020年05月25日
     * @return array
     */
    protected function getSheetColumns()
    {
        if (empty($this->columns)) {
            $start = $this->getStartSheet();

            $columns = [];
            // 通过 headers 推断需要的 columns
            foreach ($this->excel->headers() as $k => $header) {
                $columns[] = chr(ord($start) + $k);
            }

            return $columns;
        }

        return $this->columns;
    }

    /**
     * set keys
     *
     * @time 2020年05月25日
     * @return array
     */
    protected function getKeys()
    {
        if (method_exists($this->excel, 'keys')) {
            return $this->excel->keys();
        }

        return [];
    }

    /**
     * set start row
     *
     * @time 2020年05月25日
     * @return int
     */
    protected function getStartRow()
    {
        if (method_exists($this->excel, 'setRow')) {
            $this->row = $this->excel->setRow();
        }

        return $this->row;
    }

    /**
     * 设置 title
     *
     * @time 2020年05月25日
     * @return void
     */
    protected function setTitle()
    {
        if (method_exists($this->excel, 'setTitle')) {

            [$cells, $title, $style] = $this->excel->setTitle();

            $this->getWorksheet()
                 ->mergeCells($cells) // 合并单元格
                 ->setCellValue(explode(':', $cells)[0], $title)
                 ->getStyle($cells) // 设置样式
                 ->getAlignment()
                 ->setHorizontal($style);

        }
    }

    /**
     * register worksheet for excel
     *
     * @time 2020年05月25日
     * @return void
     */
    protected function registerWorksheet()
    {
        if (method_exists($this->excel, 'getWorksheet')) {
            $this->excel->getWorksheet($this->getWorksheet());
        }
    }

    /**
     * 增加 start row
     *
     * @time 2020年05月25日
     * @return void
     */
    protected function incRow()
    {
        ++$this->row;
    }

    /**
     * 设置内存限制
     *
     * @time 2020年05月26日
     * @return void
     */
    public function setMemoryLimit()
    {
        if (property_exists($this->excel, 'memory')) {
            ini_set('memory_limit', $this->excel->memory);
        }
    }
}
