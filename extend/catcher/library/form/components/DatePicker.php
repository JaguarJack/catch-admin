<?php
namespace catcher\library\form\components;

use catcher\library\form\Form;
use FormBuilder\Driver\FormComponent;
use FormBuilder\Factory\Elm;
use FormBuilder\UI\Elm\Validate;

class DatePicker extends \FormBuilder\UI\Elm\Components\DatePicker
{
    public function createValidate()
    {
        if ($this->isRange() || $this->isMultiple())
            return Elm::validateArr();
        else
            return new Validate('', Validate::TRIGGER_CHANGE);
    }
}