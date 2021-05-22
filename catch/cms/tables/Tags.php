<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Tags extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('tags')
            ->header([
                HeaderItem::label('编号')->prop('id'),

                HeaderItem::label('名称')->prop('name'),

                HeaderItem::label('创建时间')->prop('created_at'),

                HeaderItem::label('操作')->actions([
                    Actions::update(),
                    Actions::delete()
                ])
            ])
            ->withBind()
            ->withSearch([
                Search::label('名称')->name('请输入标签名称')
            ])
            ->withApiRoute('cms/tags')
            ->withActions([
                Actions::create()
            ])
            ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('tags');
    }

}