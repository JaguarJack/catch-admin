<?php
namespace catchAdmin\permissions\tables\forms;

use catcher\library\form\FormFactory;

class Factory extends FormFactory
{
    public static function from(): string
    {
        return __NAMESPACE__;
    }
}
