<?php
namespace catcher\library\excel;

interface ExcelContract
{
    public function headers(): array;

    public function sheets();
}