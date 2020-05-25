<?php
namespace catcher\library\excel;

use catcher\CatchUpload;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use think\file\UploadedFile;

class Excel
{
    use MacroExcel;

    /**
     * @var ExcelContract $excel
     */
    protected $excel;

    protected $sheets;

    protected $spreadsheet = null;

    /**
     * save
     *
     * @time 2020年05月25日
     * @param ExcelContract $excel
     * @param $path
     * @param null $disk
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @return void
     */
    public function save(ExcelContract $excel, $path, $disk = null)
    {
        $this->excel = $excel;

        // register worksheet for current excel
        $this->registerWorksheet();

        // set excel title
        $this->setTitle();

        // set excel headers
        $this->setExcelHeaders();

        // set cell width
        $this->setSheetWidth();

        // set worksheets
        $this->setWorksheets();

        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet))->save($path);

        // $this->upload($disk, $path);
    }


    /**
     *  设置 sheets
     *
     * @time 2020年05月25日
     * @throws Exception
     * @return void
     */
    protected function setWorksheets()
    {
        $keys= $this->getKeys();

        $isArray = $this->arrayConfirm();

        $worksheet = $this->getWorksheet();

        if (empty($keys)) {
            if ($isArray) {
                foreach ($this->excel->sheets() as $sheet) {
                    $worksheet->fromArray($sheet, null, $this->start . $this->row);
                    $this->incRow();
                }
            } else {
                foreach ($this->excel->sheets() as $sheet) {
                    $worksheet->fromArray($sheet->toArray(), null, $this->start . $this->row);
                    $this->incRow();
                }
            }
        } else {
            if ($isArray) {
                foreach ($this->excel->sheets() as $sheet) {
                    $worksheet->fromArray($this->getValuesByKeys($sheet, $keys), null, $this->start . $this->row);
                    $this->incRow();
                }
            } else {
                foreach ($this->excel->sheets() as $sheet) {
                    $worksheet->fromArray($this->getValuesByKeys($sheet->toArray(), $keys), null, $this->start . $this->row);
                    $this->incRow();
                }
            }
        }
    }

    /**
     * 判断 sheet 是否是 array 类型
     *
     * @time 2020年05月25日
     * @return bool
     */
    protected function arrayConfirm()
    {
        $sheets = $this->excel->sheets();

        $array = true;

        foreach ($sheets as $sheet) {
            $array = is_array($sheet);
            break;
        }

        return $array;
    }

    /**
     * 获取 item 特定 key 的值
     *
     * @time 2020年05月25日
     * @param array $item
     * @param array $keys
     * @return array
     */
    protected function getValuesByKeys(array $item, array $keys)
    {
        $array = [];

        foreach ($keys as $key) {
            $array[] = $item[$key];
        }

        return $array;
    }


    /**
     * 设置 Excel 头部
     *
     * @time 2020年05月23日
     * @throws Exception
     */
    protected function setExcelHeaders()
    {
        $worksheet = $this->getWorksheet();

        // get columns
        $columns = $this->getSheetColumns();

        // get start row
        $startRow = $this->getStartRow();

        foreach ($this->excel->headers() as $k => $header) {
            $worksheet->getCell($columns[$k] . $startRow)->setValue($header);
        }

        $this->incRow();
    }

    /**
     *  get spreadsheet
     *
     * @time 2020年05月25日
     * @return Spreadsheet
     */
    protected function getSpreadsheet()
    {
        if (!$this->spreadsheet) {
            $this->spreadsheet = new Spreadsheet();
        }

        return $this->spreadsheet;
    }

    /**
     * 获取 active sheet
     *
     * @time 2020年05月25日
     * @throws Exception
     * @return Worksheet
     */
    protected function getWorksheet()
    {
        return $this->getSpreadsheet()->getActiveSheet();
    }

    /**
     * upload
     *
     * @time 2020年05月25日
     * @param $disk
     * @param $path
     * @return string
     * @throws \Exception
     */
    protected function upload($disk, $path)
    {
        $upload = new CatchUpload;

        return ($disk ? $upload->setDriver($disk) : $upload)->upload($this->uploadedFile($path));
    }


    /**
     *  get uploaded file
     *
     * @time 2020年05月25日
     * @param $file
     * @return UploadedFile
     */
    protected function uploadedFile($file)
    {
        return new UploadedFile($file, pathinfo($file, PATHINFO_BASENAME));
    }
}
