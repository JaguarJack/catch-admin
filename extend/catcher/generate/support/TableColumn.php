<?php
namespace catcher\generate\support;

use catcher\Utils;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\db\Column;

class TableColumn
{
    /**
     * tinyint
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function tinyint(string $name, int $length): Column
    {
        return Column::tinyInteger($name);
    }

    /**
     * boolean
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function boolean(string $name, int $length): Column
    {
        return Column::boolean($name);
    }

    /**
     * smallint
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function smallint(string $name, int $length): Column
    {
        return Column::smallInteger($name);
    }

    /**
     * int
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function int(string $name, int $length): Column
    {
        return Column::integer($name);
    }

    /**
     * mediumint
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function mediumint(string $name, int $length): Column
    {
        return Column::mediumInteger($name);
    }

    /**
     * bigint
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function bigint(string $name, int $length): Column
    {
        return Column::bigInteger($name);
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
        return Column::float($name);
    }

    /**
     * 浮点数
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $precision
     * @param int $scale
     * @return Column
     */
    public function decimal(string $name, $precision = 8, $scale = 2): Column
    {
        return Column::decimal($name, $precision, $scale);
    }

    /**
     * string 类型
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function varchar(string $name, int $length): Column
    {
        return Column::string($name, $length);
    }

    /**
     * char
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function char(string $name, int $length): Column
    {
        return Column::char($name, $length);
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
     * 小文本
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function tinytext(string $name, int $length): Column
    {
        return Column::make($name, AdapterInterface::PHINX_TYPE_TEXT, ['length' => MysqlAdapter::TEXT_TINY]);
    }

    /**
     * 中长文本
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function mediumtext(string $name, int $length): Column
    {
        return Column::mediumText($name);
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
     * binary
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function binary(string $name, int $length): Column
    {
        return Column::binary($name);
    }

    /**
     * varbinary
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function varbinary(string $name, int $length): Column
    {
        return Column::make($name, AdapterInterface::PHINX_TYPE_VARBINARY);
    }

    /**
     * tinyblob
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function tinyblob(string $name, int $length): Column
    {
        return Column::make($name, AdapterInterface::PHINX_TYPE_BLOB, ['length' => MysqlAdapter::BLOB_TINY]);
    }

    /**
     * blob
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function blob(string $name, int $length): Column
    {
        return Column::make($name, AdapterInterface::PHINX_TYPE_BLOB, ['length' => MysqlAdapter::BLOB_REGULAR]);
    }

    /**
     * mediumblob
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function mediumblob(string $name, int $length): Column
    {
        return Column::make($name, AdapterInterface::PHINX_TYPE_BLOB, ['length' => MysqlAdapter::BLOB_MEDIUM]);
    }

    /**
     * longblob
     *
     * @time 2021年03月13日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function longblob(string $name, int $length): Column
    {
        return Column::make($name, AdapterInterface::PHINX_TYPE_BLOB, ['length' => MysqlAdapter::BLOB_LONG]);
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
        return Column::dateTime($name)->setOptions(['default' => 'CURRENT_TIMESTAMP']);
    }

    /**
     * 实践格式
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function time(string $name, int $length): Column
    {
        return Column::time($name);
    }

    /**
     * 时间戳
     *
     * @time 2021年03月08日
     * @param string $name
     * @param int $length
     * @return Column
     */
    public function timestamp(string $name, int $length): Column
    {
        return Column::timestamp($name)->setOptions(['default' => 'CURRENT_TIMESTAMP']);
    }

    /**
     * enum 类型
     *
     * @time 2021年03月13日
     * @param $name
     * @param $values
     * @return Column
     */
    public function enum(string $name, $values): Column
    {
        return Column::enum($name, is_string($values) ? Utils::stringToArrayBy($values) : $values);
    }

    /**
     * set 类型
     *
     * @time 2021年03月13日
     * @param string $name
     * @param $values
     * @return Column
     */
    public function set(string $name, $values): Column
    {
        $values = is_string($values) ? Utils::stringToArrayBy($values) : $values;

        return Column::make($name, AdapterInterface::PHINX_TYPE_SET, compact('values'));
    }


    /**
     * json 穿
     *
     * @time 2021年03月13日
     * @param string $name
     * @return Column
     */
    public function json(string $name): Column
    {
        return Column::json($name);
    }

    /**
     * uuid
     *
     * @time 2021年03月13日
     * @param string $name
     * @return Column
     */
    public function uuid(string $name): Column
    {
        return Column::uuid($name);
    }
}
