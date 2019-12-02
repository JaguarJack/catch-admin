<?php
namespace catchAdmin\login\request;

use app\Request;
use catchAdmin\login\validate\LoginValidate;

class LoginRequest extends Request
{
    /**
     *
     * @time 2019年11月27日
     * @return string
     */
    protected function getValidate()
    {
        // TODO: Implement getValidate() method.
        return new LoginValidate();
    }
}
