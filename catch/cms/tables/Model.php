<?php
namespace catchAdmin\cms\tables;

use catchAdmin\cms\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Model extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('model')
            ->header([
                HeaderItem::label('编号')->prop('id'),
                HeaderItem::label('模型名称')->prop('name'),
                HeaderItem::label('模型别名')->prop('alias'),
                HeaderItem::label('模型关联表')->prop('table_name'),
                HeaderItem::label('模型信息')->prop('')->width(200)->component('operate'),
                HeaderItem::label('创建时间')->prop('created_at'),
                HeaderItem::label('操作')->actions([
                    Actions::update(),
                    Actions::delete()
                ])->width(230)
            ])
            ->withActions([
                Actions::create()
            ])
            ->withSearch([
                Search::label('模型名称')->name('请填写模型名称'),
            ])
            ->forceUpdate()
            ->withApiRoute('cms/model')
            ->toTreeTable()
            ->withDialogWidth('40%')
            ->render();
    }

    public function form()
    {
        // TODO: Implement form() method.
        return Factory::create('model');
    }


}
