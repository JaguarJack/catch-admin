<?php
namespace catchAdmin\permissions\tables;


use catcher\CatchTable;
use catchAdmin\permissions\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Permission extends CatchTable
{
    public function table()
    {
        return $this->getTable('permission')
            ->header([
                HeaderItem::label('菜单名称')->prop('permission_name'),
                HeaderItem::label('路由Path')->prop('route'),
                HeaderItem::label('权限标识')->prop('actionList')->width(250)->component('actions', 'actionList'),
                HeaderItem::label('状态')->prop('hidden')->component('status'),
                HeaderItem::label('创建时间')->prop('created_at'),
                HeaderItem::label('操作')->width(250)->actions([
                    Actions::update(),
                    Actions::delete()
                ])
            ])
            ->withActions([
                Actions::create()
            ])
            ->withSearch([
                Search::label('菜单名称')->text('permission_name', '菜单名称')->clearable(true),
                Search::hidden('actionList', 'actionList')
            ])
            ->withDefaultQueryParams(['actionList'])
            ->withApiRoute('permissions')
            ->toTreeTable()
            ->render();
    }


    public function form()
    {
        // TODO: Implement form() method.
        return Factory::create('permission');
    }
}