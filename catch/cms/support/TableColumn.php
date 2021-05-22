<?php
namespace catchAdmin\cms\support;

use catchAdmin\cms\model\ModelFields;
use catchAdmin\permissions\model\search\DepartmentSearch;
use think\migration\db\Column;

class TableColumn
{
    /**
     * ['value' => 'string', 'label' => '字符串'],
    ['value' => 'int', 'label' => '整数'],
    ['value' => 'float', 'label' => '小数'],
    ['value' => 'textarea', 'label' => 'textarea文本'],
    ['value' => 'text', 'label' => '编辑器(建议)'],
    ['value' => 'longtext', 'label' => '编辑器(支持超大文本)'],
    ['value' => 'date', 'label' => '日期型'],
    ['value' => 'datetime', 'label' => '日期时间型'],
    ['value' => 'image', 'label' => '图片上传'],
    ['value' => 'images', 'label' => '多图上传'],
    ['value' => 'file', 'label' => '文件上传'],
    ['value' => 'files', 'label' => '多文件上传'],
    ['value' => 'select', 'label' => '列表'],
    ['value' => 'checkbox', 'label' => '复选框'],
    ['value' => 'password', 'label' => '密码框'],
    ['value' => 'color', 'label' => '颜色选项'],
    ['value' => 'radio', 'label' => '单选'],
    ['value' => 'city', 'label' => '省市二级级联动'],
    ['value' => 'area', 'label' => '省市区三级联动'],
     */

    protected $column;

    /**
     * TableColumn constructor.
     * @param array $field
     */
    public function __construct(array $field)
    {
       /* $column \think\migration\db\Column */
       $length =  $field['length'] ?? 0;

       $column = $this->{$field['type']}($field['name'], (int)$length);

       if ($field['default_value']) {
           $column->setDefault($field['default_value'] ?: '');
       }

       $column->setComment($field['title'] ? : '');

       if (isset($field['is_unique']) && $field['is_unique'] == ModelFields::IS_UNIQUE) {
           $column->setUnique();
       }

        if (isset($field['is_index']) && $field['is_index'] == ModelFields::IS_INDEX) {
            $column->be_index = true;
        }

        $this->column = $column;
    }

    /**
     * 获取结果
     *
     * @time 2021年03月08日
     * @return mixed
     */
    public function get()
    {
        return $this->column;
    }

    /**
     * string 类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function string(string $name, int $length): Column
    {
        return Column::string($name, $length);
    }

    /**
     * int 类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function int(string $name, int $length): Column
    {
        return Column::integer($name)->setLimit($length);
    }

    /**
     * 浮点数
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function float(string $name, int $length): Column
    {
        return Column::float($name)->setLimit($length);
    }

    /**
     * varchar 类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function textarea(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(2000);
    }

    /**
     * 普通文本
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function text(string $name, int $length): Column
    {
        return Column::text($name);
    }

    /**
     * 超大文本
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function longtext(string $name, int $length): Column
    {
        return Column::longText($name);
    }

    /**
     * 时间类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function date(string $name, int $length): Column
    {
        return Column::date($name);
    }

    /**
     * 日期时间
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function datetime(string $name, int $length): Column
    {
        return Column::dateTime($name);
    }

    /**
     * 图片存储
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function image(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(255);
    }

    /**
     * 图片集合存储
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function images(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(1000);
    }

    /**
     * 文件存储
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function file(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(255);
    }

    /**
     * 文件集合
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function files(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(1000);
    }

    /**
     * 列表类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function select(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(20);
    }

    /**
     * checkbox 类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function checkbox(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(20);
    }

    /**
     * 密码
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function password(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(255);
    }

    /**
     * 颜色
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function color(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(50);
    }

    /**
     * 单选
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function radio(string $name, int $length): Column
    {
        return Column::string($name)->setLimit(20);
    }

    /**
     * 省市
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function city(string $name, int $length): Column
    {
        // province_name/city_name
        return Column::string($name)->setLimit(255);
    }

    /**
     * 省市区
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function area(string $name, int $length): Column
    {
        // province_name/city_name/district_name
       return Column::string($name)->setLimit(255);
    }
}
