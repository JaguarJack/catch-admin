<?php
namespace catchAdmin\index\controller;


use catcher\base\BaseController;

class Index extends BaseController
{
    public function index(): string
    {
        return $this->fetch();
    }
}