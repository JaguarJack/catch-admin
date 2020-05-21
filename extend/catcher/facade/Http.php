<?php
namespace catcher\facade;

use think\Facade;

class Http extends Facade
{
    protected static function getFacadeClass()
    {
        return \catcher\library\client\Http::class;
    }
}
