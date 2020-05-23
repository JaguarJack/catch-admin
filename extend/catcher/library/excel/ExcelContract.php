<?php
namespace catcher\library\excel;

interface Excel
{
    public function title(): string;

    public function headers(): array;

    public function sheets(): array;

    public function filename():string;

}