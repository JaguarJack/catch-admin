<?php
namespace catchAdmin\user\request;

use catchAdmin\user\validate\UpdateValidate;
use catcher\base\BaseRequest;

class UpdateRequest extends BaseRequest
{
    protected function getValidate()
    {
        // TODO: Implement getValidate() method.
        return new UpdateValidate();
    }
}
