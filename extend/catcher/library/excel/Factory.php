<?php
declare(strict_types=1);

namespace catcher\library\excel;


use catcher\exceptions\FailedException;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Factory
{

    public static function make($type)
    {
        if ($type === 'xlsx') {
            return app(Xlsx::class);
        }

        if ($type === 'xls') {
            return app(Xls::class);
        }

        if ($type === 'csv') {
            return app(Csv::class);
        }

        throw new FailedException(sprintf('Type [%s] not support', $type));
    }
}
