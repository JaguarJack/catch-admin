<?php
namespace catcher\library\form\components;

use FormBuilder\Driver\FormComponent;
use FormBuilder\Factory\Elm;

class Editor extends FormComponent
{
    protected $defaultProps = [
        'type' => 'editor'
    ];

    public function createValidate()
    {
        // TODO: Implement createValidate() method.
        return Elm::validateStr();
    }
}