<?php
namespace catcher\library\excel;

interface ExcelContract
{
    public function headers(): array;

    public function keys(): array ;

    public function sheets();
}