<?php
namespace catchAdmin\permissions\tables;


use catcher\CatchTable;
use catchAdmin\permissions\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Role extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('role')
            ->header([
                HeaderItem::label('角色名称')->prop('role_name')->width(300),
                HeaderItem::label('角色标识')->prop('identify')->width(300),
                HeaderItem::label('角色描述')->prop('description'),
                HeaderItem::label('创建时间')->prop('created_at'),
                HeaderItem::label('操作')->width(250)->actions([
                    Actions::update(), Actions::delete()
                ])
            ])
            ->withSearch([
                Search::label('角色名称')->text('role_name', '角色名称'),
            ])
            ->withApiRoute('roles')
            ->withActions([
                Actions::create()
            ])
            ->withBind()
            ->withDialogWidth('40%')
            ->toTreeTable()
            ->forceUpdate()
            ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('role');
    }
}