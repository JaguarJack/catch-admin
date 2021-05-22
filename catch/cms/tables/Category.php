<?php
namespace catchAdmin\cms\tables;

use catchAdmin\cms\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\Excel;
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
                        HeaderItem::label('自定义链接')->prop('url')->withCopyComponent(),
                        HeaderItem::label('栏目类型')->prop('type')->withSelectComponent([
                            ['value' => 1, 'label' => '列表模式'],
                            ['value' => 2, 'label' => '单页模式'],
                            ['value' => 3, 'label' => '封面模式'],
                        ]),
                        HeaderItem::label('投稿')->prop('is_can_contribute')->withSwitchComponent([
                            ['value' => 1, 'label' => '是'],
                            ['value' => 2, 'label' => '否'],
                        ]),
                        HeaderItem::label('评论')->prop('is_can_comment')->withSwitchComponent(),
                        HeaderItem::label('状态')->prop('status')->withSwitchComponent(),
                        HeaderItem::label('权重')->prop('weight')->withEditNumberComponent(),
                        // HeaderItem::label('创建时间')->prop('created_at'),
                        HeaderItem::label('操作')->actions([
                            Actions::update(),
                            Actions::delete()
                        ])->width(230)
                    ])
                    ->withActions([
                        Actions::create()
                    ])
                    ->withSearch([
                        Search::label('分类名称')->name('请输入分类名称'),
                        Search::label('状态')->status(),
                    ])
                    ->forceUpdate()
                    ->withApiRoute('cms/category')
                    ->toTreeTable()
                    ->withDialogWidth('40%')
                    ->render();
    }

    public function form()
    {
        // TODO: Implement form() method.
        return Factory::create('category');
    }


}
