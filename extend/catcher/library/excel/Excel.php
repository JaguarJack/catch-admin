<?php
declare(strict_types=1);

namespace catcher\library\excel;

use catcher\CatchUpload;
use catcher\exceptions\FailedException;
use catcher\Utils;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use think\file\UploadedFile;
use think\helper\Str;

class Excel
{
    use MacroExcel;

    /**
     * @var ExcelContract $excel
     */
    protected $excel;

    protected $sheets;

    protected $spreadsheet = null;

    protected $extension = 'xlsx';

    /**
     * save
     *
     * @time 2020年05月25日
     * @param ExcelContract $excel
     * @param $path
     * @param string $disk
     * @return mixed
     * @throws Exception
     */
    public function save(ExcelContract $excel, $path, $disk = 'local')
    {
        $this->excel = $excel;

        $this->init();

        !is_dir($path) && mkdir($path, 0777, true);

        $file = $path . date('YmdHis').Str::random(6) . '.' .$this->extension;
        Factory::make($this->extension)
                ->setSpreadsheet($this->spreadsheet)
                ->save($file);

         if (!file_exists($file)) {
             throw new FailedException($file . ' generate failed');
         }

         if ($disk) {
            $file = $this->upload($disk, $file);
         }

         return ['url' => $file];
    }

    /**
     * set extension
     *
     * @time 2020年09月08日
     * @param $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * init excel
     *
     * @time 2020年05月25日
     * @throws Exception
     * @return void
     */
    protected function init()
    {
        $this->setMemoryLimit();
        // register worksheet for current excel
        $this->registerWorksheet();
        // before save excel
        $this->before();
        // set excel title
        $this->setTitle();
        // set excel headers
        $this->setExcelHeaders();
        // set cell width
        $this->setSheetWidth();
        // set worksheets
        $this->setWorksheets();
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
        if ($disk == 'local') {
            return $this->local($path);
        }
        $upload = new CatchUpload;

        return ($disk ? $upload->setDriver($disk) : $upload)->upload($this->uploadedFile($path));
    }

    /**
     * 返回本地下载地址
     *
     * @param $path
     * @time 2020年09月08日
     * @return mixed
     */
    protected function local($path)
    {
        return \config('filesystem.disks.local')['domain'] . '/' .

            str_replace('\\', '/', str_replace(Utils::publicPath(), '', $path));
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
