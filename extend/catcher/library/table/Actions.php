<?php

namespace catcher\library\table;

use catcher\library\table\components\Button;

class Actions
{
    /**
     * 创建按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return mixed
     */
    public static function create(string $text = '新建', string $event = 'handleCreate')
    {
        return self::normal($text, 'primary',$event)->icon('el-icon-plus');
    }

    /**
     * 更新按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return mixed
     */
    public static function update(string $text = '更新', string $event = 'handleUpdate')
    {
        return self::normal($text, 'primary', $event)->icon('el-icon-edit');
    }

    /**
     * 删除按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return mixed
     */
    public static function delete(string $text = '删除', string $event = 'handleDel')
    {
        return self::normal($text, 'danger', $event)->icon('el-icon-delete');
    }

    /**
     * 查询按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string|null $event
     * @return mixed
     */
    public static function view(string $text = '查看', string $event = null)
    {
        return self::normal($text, '', $event)->icon('el-icon-eye');
    }

    /**
     * normal
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $type
     * @param string|null $event
     * @return Button
     */
    public static function normal(string $text, $type = '', string $event = null): Button
    {
        $button = (new Button)
            ->size('mini')
            ->type($type)
            ->text($text);

        if ($event) {
            return $button->click($event);
        }

        return $button;
    }


    /**
     * 导出按钮
     *
     * @time 2021年04月02日
     * @return mixed
     */
    public static function export()
    {
        return self::normal('导出', 'success','handleExport')->icon('el-icon-download');
    }

    /**
     * 导入按钮
     *
     * @time 2021年04月02日
     * @return mixed
     */
    public static function import()
    {
        return self::normal('导入', 'warning', 'handleImport')->icon('el-icon-upload2');
    }

}