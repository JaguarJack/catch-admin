<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Comments extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('comments')
                   ->header([
                       HeaderItem::label('编号')->prop('id')->width(50),

                       HeaderItem::label('文章标题')->prop('title'),

                       HeaderItem::label('评论')->prop('content'),

                       HeaderItem::label('用户昵称')->prop('username'),

                       HeaderItem::label('ip')->prop('ip'),

                       HeaderItem::label('状态')->prop('status')->withSwitchComponent(),

                       HeaderItem::label('创建时间')->prop('created_at'),

                       HeaderItem::label('操作')->actions([
                           Actions::delete(),
                       ])
                   ])
                   ->withSearch([
                       Search::input('title', '文章标题')->clearable(true),

                       Search::input('username', '用户名称')->clearable(true),

                       Search::label('状态')->status('请选择状态')->clearable(true),
                   ])
                   ->withBind()
                   ->withApiRoute('/cms/comments')
                   ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('comments');
    }
}