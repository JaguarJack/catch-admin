<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;

class Articles extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('articles')
                   ->header([
                       HeaderItem::label('编号')->prop('id'),

                       HeaderItem::label('栏目')->prop('category'),

                       HeaderItem::label('标题')->prop('title'),

                       HeaderItem::label('权重')->prop('weight')->withEditNumberComponent(),

                       HeaderItem::label('状态')->prop('status')->withSwitchComponent(),

                       HeaderItem::label('创建时间')->prop('created_at'),

                       HeaderItem::label('操作')->actions([
                           Actions::update()->to('/cms/articles/detail'),
                           Actions::delete()
                       ])
                   ])
                   ->withBind()
                   ->withApiRoute('cms/articles')
                   ->withActions([
                       Actions::create()->to('/cms/articles/detail/')
                   ])
                   ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('articles');
    }
    
}