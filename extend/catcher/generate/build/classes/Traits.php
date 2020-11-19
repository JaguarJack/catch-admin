<?php
namespace catcher\generate\classes;

class Traits extends Iteration
{
    protected $trait = [];

    public function name($name)
    {
        if (!empty($this->trait)) {
            $this->data[] = $this->trait;

            $this->trait = [];

            return $this;
        }

        $this->trait['name'] = $name;

        return $this;
    }

    public function adaptation($name)
    {
        $this->trait['adaptation'] = $name;
    }

    public function insteadof($name)
    {
        $this->trait['insteadof'] = $name;
    }

    public function as($as)
    {
        $this->trait['as'] = $as;
    }
}
