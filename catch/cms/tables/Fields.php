<?php
namespace catchAdmin\cms\tables;

use catchAdmin\cms\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Fields extends CatchTable
{
    public function table()
    {
        return $this->getTable('fields')
            ->header([
                HeaderItem::label('编号')->prop('id')->width(80),
                HeaderItem::label('字段名称')->prop('name'),
                HeaderItem::label('label名称')->prop('title'),
                HeaderItem::label('类型')->prop('type'),
                // HeaderItem::label('列表显示')->prop('use_at_list')->withSwitchComponent( ),
                // HeaderItem::label('搜索')->prop('use_at_search')->withSwitchComponent(),
                // HeaderItem::label('详情')->prop('use_at_detail')->withSwitchComponent(),
                HeaderItem::label('状态')->prop('status')->withSwitchComponent( ),
                HeaderItem::label('设置索引')->prop('is_index')->withSwitchComponent( ),

                HeaderItem::label('操作')->width(260)->isBubble()->actions([
                    Actions::update(),
                    Actions::delete()
                ]),
            ])
            ->withActions([
                Actions::create(),
            ])
            ->withSearch([
                Search::hidden('model_id', '')
            ])
            ->withHiddenPaginate()
            ->withDialogWidth('40%')
            ->withDefaultQueryParams(['model_id'])
            ->withBind()
            ->withApiRoute('cms/modelFields')
            ->render();
    }

    public function form()
    {
        // TODO: Implement form() method.
        return Factory::create('field');
    }
}