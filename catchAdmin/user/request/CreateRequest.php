<?php
namespace catchAdmin\user\request;

use catchAdmin\user\validate\CreateValidate;
use catcher\base\BaseRequest;

class CreateRequest extends BaseRequest
{

    protected function getValidate()
    {
        // TODO: Implement getValidate() method.
        return new CreateValidate();
    }
}