<?php
namespace catchAdmin\index\controller;

use catcher\base\CatchController;

class Index extends CatchController
{
    /**
     *
     * @time 2019年12月11日
     * @throws \Exception
     * @return string
     */
    public function index(): string
    {
        return $this->fetch();
    }

    /**
     *
     * @time 2019年12月11日
     * @throws \Exception
     * @return string
     */
    public function theme(): string
    {
        return $this->fetch();
    }
}