<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;

class UsedAt extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return [];
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('UsedAt');
    }
    
}