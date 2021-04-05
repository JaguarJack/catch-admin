<?php
namespace catchAdmin\system\tables;

use catcher\CatchTable;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;


class OperateLog extends CatchTable
{
    protected function form()
    {
        // TODO: Implement form() method.
        return [];
    }

    protected function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('operateLog')
            ->header([
                HeaderItem::label()->selection(),
                HeaderItem::label('编号')->prop('id'),
                HeaderItem::label('操作人')->prop('creator'),
                HeaderItem::label('操作模块')->prop('module'),
                HeaderItem::label('操作菜单')->prop('operate'),
                HeaderItem::label('菜单标识')->prop('route'),
                HeaderItem::label('请求方式')->prop('method'),
                HeaderItem::label('参数')->prop('params')->component('params'),
            ])
            ->withApiRoute('log/operate')
            ->withSearch([
                Search::text('creator', '请输入操作人'),
                Search::text('module', '请输入模块'),
                Search::select('method', '请选择请求方法', [
                    ['label' => 'GET', 'value' => 'GET'],
                    ['label' => 'POST', 'value' => 'POST'],
                    ['label' => 'PUT', 'value' => 'PUT'],
                    ['label' => 'DELETE', 'value' => 'DELETE'],
                ])
            ])
            ->selectionChange()
            ->render();

    }
}