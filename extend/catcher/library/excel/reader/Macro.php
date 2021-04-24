<?php
declare(strict_types=1);

namespace catcher\library\excel\reader;

trait Macro
{
    /**
     * 移除不需要的列
     *
     * @time 2021年04月21日
     * @param ...$indexes
     * @return mixed
     */
    public function remove(...$indexes)
    {
        foreach ($indexes as $index) {
            unset($this->sheets[$index]);
        }

        return $this;
    }

    /**
     * 设置 memory
     *
     * @time 2021年04月21日
     * @param int $memory
     * @return mixed
     */
    public function memory(int $memory)
    {
        ini_set('memory_limit', $memory . 'M');

        return $this;
    }


    /**
     * 处理
     *
     * @time 2021年04月23日
     * @return array
     */
    protected function dealWith(): array
    {
        $headers = $this->headers();

        $data = [];

        foreach ($this->sheets as &$sheet) {
            $d = [];
            foreach ($headers as $k => $header) {
                $d[$header] = method_exists($this, 'deal' . ucfirst($header)) ?

                        $this->{'deal' . ucfirst($header)}($sheet) : $sheet[$k];
            }

            $data[] = $d;
        }

        return $data;
    }
}
