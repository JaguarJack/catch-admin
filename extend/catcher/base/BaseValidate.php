<?php
namespace catcher\base;

use catcher\validates\Uniques;
use think\Validate;

abstract class BaseValidate extends Validate
{
    public function __construct()
    {
        parent::__construct();

        $this->register();

        $this->rule = $this->getRules();
    }


    abstract protected function getRules(): array ;


    private function register()
    {
        if (!empty($this->newValidates())) {
            foreach ($this->newValidates() as $validate) {
                $this->extend($validate->type(), [$validate, 'verify'], $validate->message());
            }
        }
    }


    private function newValidates()
    {
        return [
        ];
    }
}
