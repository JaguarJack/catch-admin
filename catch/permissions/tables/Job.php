<?php
namespace catchAdmin\permissions\tables;


use catcher\CatchTable;
use catchAdmin\permissions\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class Job extends CatchTable
{
    public function table()
    {
        return $this->getTable('job')
                    ->header([
                        HeaderItem::label('')->type('selection'),
                        HeaderItem::label('岗位名称')->prop('job_name'),
                        HeaderItem::label('编码')->prop('coding'),
                        HeaderItem::label('状态')->prop('status')->withSwitchComponent(),
                        HeaderItem::label('创建时间')->prop('created_at'),
                        HeaderItem::label('操作')->width(250)->actions([
                            Actions::update(),
                            Actions::delete()
                        ])
                    ])
                    ->withActions([
                      Actions::create()
                    ])
                    ->withSearch([
                        Search::label('岗位名称')->text('job_name', '岗位名称')
                    ])
                    ->withApiRoute('jobs')
                    ->selectionChange()
                    ->render();
    }


    public function form()
    {
        // TODO: Implement form() method.
        return Factory::create('job');
    }
}