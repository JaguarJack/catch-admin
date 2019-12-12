<?php
namespace catcher\base;

use catcher\validates\Sometimes;
use think\Validate;

abstract class CatchValidate extends Validate
{
    public function __construct()
    {
        parent::__construct();

        $this->register();

        $this->rule = $this->getRules();
    }


    abstract protected function getRules(): array ;

    /**
     *
     * @time 2019年12月07日
     * @return void
     */
    private function register(): void
    {
        if (!empty($this->newValidates())) {
            foreach ($this->newValidates() as $validate) {
                $this->extend($validate->type(), [$validate, 'verify'], $validate->message());
            }
        }
    }

    /**
     *
     * @time 2019年12月07日
     * @return array
     */
    private function newValidates(): array
    {
        return [
            new Sometimes(),
        ];
    }
}
