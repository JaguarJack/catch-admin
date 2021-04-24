<?php
namespace catcher\library\table;


/**
 *
 * @time 2021年04月21日
 */
class Excel
{
    protected static $label;

    protected $sheets = [];

    /**
     * name
     *
     * @time 2021年04月21日
     * @param string $name
     * @return $this
     */
    public function prop(string $name): Excel
    {
        $this->sheets['prop'] = $name;

        return $this;
    }

    /**
     * label
     *
     * @time 2021年04月21日
     * @param string $label
     * @return $this
     */
    protected function label(string $label): Excel
    {
        $this->sheets['label'] = $label;

        return $this;
    }

    /**
     * options
     *
     * @time 2021年04月21日
     * @param array $options
     * @return $this
     */
    public function options(array $options): Excel
    {
        $this->sheets['options'] = $options;

        return $this;
    }

    /**
     * 导入
     *
     * @time 2021年04月22日
     * @param bool $import
     * @return $this
     */
    public function import(bool $import = true): Excel
    {
        $this->sheets['import'] = $import;

        return $this;
    }

    /**
     * 导出
     *
     * @time 2021年04月22日
     * @param bool $export
     * @return $this
     */
    public function export(bool $export = true): Excel
    {
        $this->sheets['export'] = $export;

        return $this;
    }


    /**
     * 渲染
     *
     * @time 2021年04月21日
     * @return array
     */
    public function render(): array
    {
        return $this->sheets;
    }

    /**
     * 静态访问
     *
     * @time 2021年04月21日
     * @param $method
     * @param $params
     * @return false|mixed
     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([new self(), $method], $params);
    }
}