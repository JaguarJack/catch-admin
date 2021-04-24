<?php
declare(strict_types=1);

namespace catcher\library\excel\reader;

use catcher\CatchUpload;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

abstract class Reader
{
    use Macro;

    /**
     * 当前的 sheet
     *
     * false 代表获取全部 sheets
     *
     * @var bool
     */
    protected $active = true;


    protected $sheets;

    /**
     * 导入
     *
     * @time 2021年04月21日
     * @param $file
     * @return Reader
     */
    public function import($file): Reader
    {
        $file = (new CatchUpload)->setPath('excel')->toLocal($file);

        $reader = Factory::make($file);
        // 设置只读
        $reader->setReadDataOnly(true);

        /* @var $spreadsheet Spreadsheet */
        $spreadsheet = $reader->load($file);

        if ($this->active) {
            $this->sheets = $spreadsheet->getActiveSheet()->toArray();
        } else {
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                $this->sheets[] = $sheet->toArray();
            }
        }

        return $this;
    }

    /**
     * 必须实现的方法
     *
     * @time 2021年04月21日
     * @return mixed
     */
    abstract public function headers();


    /**
     * 数据处理
     *
     * @time 2021年04月23日
     * @param callable $callback
     * @return mixed
     */
    public function then(callable $callback)
    {
        return $callback($this->dealWith());
    }
}