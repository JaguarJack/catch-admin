<?php
namespace catchAdmin\permissions\tables;

use catchAdmin\permissions\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;
use catcher\library\table\Table;

class Department extends CatchTable
{
    /**
     * table
     *
     * @time 2021年03月29日
     * @return array
     */
    protected function table(): array
    {
        // TODO: Implement table() method.
        return $this->getTable('department')->header([
            HeaderItem::label('部门名称')->prop('department_name'),
            HeaderItem::label('排序')->prop('sort')->withEditNumberComponent(),
            HeaderItem::label('状态')->prop('status')->withSwitchComponent(),
            HeaderItem::label('创建时间')->prop('created_at'),
            HeaderItem::label('操作')->width(260)->actions([
                Actions::update(),
                Actions::delete(),
            ])
        ])->withApiRoute('departments')->withActions([
            Actions::create()
        ])->withSearch([
            Search::label('部门名称')->text('department_name', '请输入部门名称'),
            Search::label('状态')->status()
        ])->withDialogWidth('35%')
            ->toTreeTable()->render();
    }

    /**
     * form 方式
     *
     * @time 2021年03月29日
     * @return array
     */
    protected function form(): array
    {
        return Factory::create('department');
    }
}