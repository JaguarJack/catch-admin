<?php
declare(strict_types=1);

namespace catcher\library\excel;

interface ExcelContract
{
    public function headers(): array;

    public function sheets();
}