<?php
namespace catchAdmin\system\tables;

use catchAdmin\system\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class SensitiveWord extends CatchTable
{
    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('SensitiveWord');
    }

    protected function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('SensitiveWord')
                    ->header([
                        HeaderItem::label()->selection(),
                        HeaderItem::label('编号')->prop('id'),
                        HeaderItem::label('敏感词')->prop('word'),
                        HeaderItem::label('创建人')->prop('creator'),
                        HeaderItem::label('创建时间')->prop('created_at'),
                        HeaderItem::label('更新时间')->prop('updated_at'),
                        HeaderItem::label('操作')->actions([
                            Actions::update(),
                            Actions::delete()
                        ])
                    ])
                    ->withActions([
                        Actions::create()
                    ])
                    ->withDialogWidth('35%')
                    ->withApiRoute('sensitive/word')
                    ->withSearch([
                        Search::text('word', '输入敏感词')
                    ])
                    ->selectionChange()
                    ->render();
    }
}