<?php
namespace catchAdmin\cms\controller;

use catcher\base\CatchController;
use catcher\CatchResponse;

class TestController extends CatchController
{
    public function index()
    {
        return CatchResponse::success('Hello CatchAdmin');
    }
}