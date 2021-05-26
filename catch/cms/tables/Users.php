<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Users extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('users')
                   ->header([
                       HeaderItem::label('编号')->prop('id')->width(50),

                       HeaderItem::label('用户名')->prop('username'),

                       HeaderItem::label('头像')->prop('avatar')->withPreviewComponent(),

                       HeaderItem::label('邮箱')->prop('email'),

                       HeaderItem::label('手机号')->prop('mobile'),

                       HeaderItem::label('状态')->prop('status')->withSwitchComponent(),

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
                       Search::input('username', '用户名')->clearable(true),

                       Search::input('email', '邮箱')->clearable(true),

                       Search::input('mobile', '手机号')->clearable(true),

                       Search::label('状态')->status()->clearable(true),
                   ])
                   ->withBind()
                   ->withApiRoute('/cms/users')
                   ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('users');
    }
    
}