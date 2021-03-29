<?php

namespace catcher\library\table;

use catcher\library\components\Button;

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
        return (new Button)->icon('el-icon-plus')
            ->text($text)
            ->type('primary')
            ->size('mini')
            ->click($event);
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
        return (new Button)->icon('el-icon-edit')
            ->text($text)
            ->size('mini')
            ->type('primary')
            ->click($event);
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
        return (new Button)->icon('el-icon-delete')
                            ->text($text)->type('danger')
                            ->size('mini')
                            ->click($event);
    }

    /**
     * 查询按钮
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string $event
     * @return mixed
     */
    public static function view(string $text = '查看', string $event = null)
    {
        $button = (new Button)->icon('el-icon-eye')
            ->size('mini')
            ->text($text);

        if ($event) {
            return $button->click($event);
        }

        return $button;
    }

    /**
     * normal
     *
     * @time 2021年03月23日
     * @param string $text
     * @param string|null $event
     * @return mixed
     */
    public static function normal(string $text, string $event = null)
    {
        $button = (new Button)
            ->size('mini')
            ->text($text);

        if ($event) {
            return $button->click($event);
        }

        return $button;
    }
}