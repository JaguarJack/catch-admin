<?php
namespace catchAdmin\monitor\tables;

use catcher\CatchTable;
use catchAdmin\monitor\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class CrontabLog extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('CrontabLog')
                   ->header([
                       HeaderItem::label('')->selection(),
                       HeaderItem::label('编号')->prop('id'),
                       HeaderItem::label('名称')->prop('name'),
                       // HeaderItem::label('分组')->prop('group'),
                       HeaderItem::label('调用目标类')->prop('task'),
                       HeaderItem::label('耗时/ms')->prop('used_time'),
                       HeaderItem::label('错误日志')->prop('error_message'),
                       HeaderItem::label('状态')->prop('status')->component('status'),
                       HeaderItem::label('创建时间')->prop('created_at'),
                       HeaderItem::label('操作')->actions([
                           Actions::delete()
                       ])
                   ])
                   ->withSearch([
                       Search::label('任务名称')->name('请填写任务名称'),
                       Search::label('分组')->select('group', '请选择分组',
                           Search::options()->add('默认', 1)->add('系统', 1)->render()
                       ),
                       Search::label('状态')->status(),
                       Search::label('开始时间')->startAt(),
                       Search::label('结束时间')->endAt(),
                       Search::hidden('crontab_id', 0),
                   ])
                   ->withDefaultQueryParams([
                       'crontab_id'
                   ])
                   ->withBind()
                   ->withApiRoute('monitor/crontab/log/list')
                   ->selectionChange()
                   ->render();

    }

    protected function form()
    {
        // TODO: Implement form() method.
        return [];
    }
    
}