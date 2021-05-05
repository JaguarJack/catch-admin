<?php
namespace catchAdmin\permissions\tables;


use catcher\CatchTable;
use catchAdmin\permissions\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class User extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('user')
                    ->header([
                        HeaderItem::label('')->selection(),
                        HeaderItem::label('用户名')->prop('username'),
                        HeaderItem::label('头像')->prop('avatar')->withPreviewComponent(),

                        HeaderItem::label('邮箱')->prop('email'),
                        HeaderItem::label('状态')->prop('status')->component('status', 'status'),
                        HeaderItem::label('创建时间')->prop('created_at'),
                        HeaderItem::label('操作')->width(200)->actions([
                            Actions::update(), Actions::delete()
                        ])
                    ])
                    ->withSearch([
                        Search::label('用户名')->text('username', '用户名'),
                        Search::label('邮箱')->text('email', '邮箱'),
                        Search::label('状态')->status(),
                        Search::hidden('department_id', '')
                    ])
                    ->withApiRoute('users')
                    ->withActions([
                        Actions::create(),
                        Actions::export()
                    ])
                    ->withExportRoute('user/export')
                    ->withFilterParams([
                        'username' => '',
                        'email'    => '',
                        'status'   => '',
                        'department_id' => ''
                    ])
                    ->selectionChange()
                    ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('user');
    }
}