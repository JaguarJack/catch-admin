<?php
namespace catcher\generate\classes;

class Classes extends Iteration
{
    /**
     *
     * @time 2020年11月17日
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->data['name'] = $name;

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @param $extend
     * @return $this
     */
    public function extend($extend)
    {
        $this->data['extend'] = $extend;

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @param $interfaces
     * @return $this
     */
    public function interfaces($interfaces)
    {
        $this->data['interfaces'] = $interfaces;

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function isAbstract()
    {
        $this->data['type'] = 'Abstract';

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @return $this
     */
    public function isFinal()
    {
        $this->data['type'] = 'Final';

        return $this;
    }

    /**
     * @time 2020年11月17日
     * @param $type
     * @param $param
     * @return void
     */
    public function construct($type, $param)
    {
        $this->data['construct'][] = [$type, $param];
    }
}
