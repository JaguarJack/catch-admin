<?php
namespace catchAdmin\system\tables;

use catchAdmin\system\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;


class LoginLog extends CatchTable
{
    protected function form()
    {
        // TODO: Implement form() method.
        return [];
    }

    protected function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('loginLog')
                    ->header([
                        HeaderItem::label()->selection(),
                        HeaderItem::label('登陆用户')->prop('login_name'),
                        HeaderItem::label('登陆IP')->prop('login_ip'),
                        HeaderItem::label('客户端')->prop('browser'),
                        HeaderItem::label('系统')->prop('os'),
                        HeaderItem::label('登陆状态')->prop('status')->component('status'),
                        HeaderItem::label('登陆时间')->prop('login_at')->component('loginAt'),
                    ])
                    ->withApiRoute('log/login')
                    ->withSearch([
                        Search::startAt(),
                        Search::label('结束时间')->endAt()
                    ])
                    ->selectionChange()
                    ->render();

    }
}