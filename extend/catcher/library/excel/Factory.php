<?php
declare(strict_types=1);

namespace catcher\library\excel;


use catcher\exceptions\FailedException;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Factory
{

    public static function make($type, $spreadsheet)
    {
        if ($type === 'xlsx') {
            return app(Xlsx::class)->setSpreadsheet($spreadsheet);
        }

        if ($type === 'xls') {
            return new Xls($spreadsheet);
        }

        if ($type === 'csv') {
            return (new Csv($spreadsheet))->setUseBOM('utf-8');
        }

        throw new FailedException(sprintf('Type [%s] not support', $type));
    }
}
