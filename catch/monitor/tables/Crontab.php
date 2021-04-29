<?php
namespace catchAdmin\monitor\tables;

use catcher\CatchTable;
use catchAdmin\monitor\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Crontab extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('Crontab')
                   ->header([
                       HeaderItem::label('')->selection(),
                       HeaderItem::label('编号')->prop('id')->width(80),
                       HeaderItem::label('名称')->prop('name'),
                       HeaderItem::label('分组')->prop('group')->withSelectComponent([
                           ['value' => 1, 'label' => '默认'],
                           ['value' => 2, 'label' => '系统'],
                       ]),
                       HeaderItem::label('指令')->prop('task')->width(120),
                       HeaderItem::label('cron表达式')->prop('cron')->width(120),
                       HeaderItem::label('状态')->prop('status')->withSwitchComponent(),
                       HeaderItem::label('创建时间')->prop('created_at'),
                       HeaderItem::label('操作')->actions([
                           Actions::update(),
                           Actions::delete(),
                           Actions::normal('日志')->to('/monitor/crontab/log'),
                       ])->width(260)
                   ])
                   ->withBind()
                   ->withSearch([
                        Search::label('任务名称')->name('请填写任务名称'),
                        Search::label('状态')->status()
                   ])
                   ->selectionChange()
                   ->withApiRoute('monitor/crontab')
                   ->withActions([
                       Actions::create()
                   ])
                   ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('Crontab');
    }
}