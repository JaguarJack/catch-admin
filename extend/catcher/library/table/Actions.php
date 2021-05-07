<?php

namespace catcher\library\table;

use catcher\library\table\components\Button;

class Actions
{
    protected static $noText = false;

    /**
     * 创建按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return Button
     */
    public static function create(string $text = '新建', string $event = 'handleCreate'): Button
    {
        return self::normal($text, 'primary',$event)->icon('el-icon-plus');
    }

    /**
     * 更新按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return Button
     */
    public static function update(string $text = '更新', string $event = 'handleUpdate'): Button
    {
        return self::normal($text, 'primary', $event)->icon('el-icon-edit');
    }

    /**
     * 删除按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return Button
     */
    public static function delete(string $text = '删除', string $event = 'handleDel'): Button
    {
        return self::normal($text, 'danger', $event)->icon('el-icon-delete');
    }

    /**
     * 查询按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $type
     * @param string|null $event
     * @return Button
     */
    public static function view(string $text = '查看', $type = 'success', string $event = 'handleView'): Button
    {
        return self::normal($text, $type, $event)->icon('el-icon-view');
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
            ->text(self::$noText ? '' : $text);

        if ($event) {
            return $button->click($event);
        }

        return $button;
    }


    /**
     * 导出按钮
     *
     * @time 2021年04月02日
     * @return Button
     */
    public static function export(): Button
    {
        return self::normal('导出', 'success','handleExport')->icon('el-icon-download');
    }

    /**
     * 导入按钮
     *
     * @time 2021年04月02日
     * @return Button
     */
    public static function import(): Button
    {
        return self::normal('导入', 'warning', 'handleImport')->icon('el-icon-upload2');
    }

}