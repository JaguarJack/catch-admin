<?php
namespace catchAdmin\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index(): string
    {
        return $this->fetch('index::index');
    }
}