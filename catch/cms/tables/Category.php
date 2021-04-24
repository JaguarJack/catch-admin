<?php
namespace catchAdmin\cms\table;

use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Category extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('category')
                    ->header([
                        HeaderItem::label('分类名称')->prop('name'),
                        HeaderItem::label('自定义链接')->prop('url'),
                        HeaderItem::label('栏目类型')->prop('type')->component('type'),
                        HeaderItem::label('投稿')->prop('is_can_contribute')->withSwitchComponent(),
                        HeaderItem::label('评论')->prop('is_can_comment')->withSwitchComponent(),
                        HeaderItem::label('状态')->prop('status')->withSwitchComponent(),
                        HeaderItem::label('权重')->prop('weight')->withEditNumberComponent(),
                        HeaderItem::label('创建时间')->prop('created_at'),
                        HeaderItem::label('操作')->actions([
                            Actions::update(),
                            Actions::delete()
                        ])
                    ])
                    ->withActions([
                        Actions::create()
                    ])
                    ->withSearch([
                        Search::label('分类名称')->name('请输入分类名称'),
                        Search::label('状态')->name('请选择状态'),
                    ])
                    ->toTreeTable()
                    ->render();
    }

    public function form()
    {
        // TODO: Implement form() method.

    }


}
