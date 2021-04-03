<?php
namespace catchAdmin\system\tables;

use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;


class Database extends CatchTable
{
    protected function form()
    {
        // TODO: Implement form() method.
        return [];
    }

    protected function table()
    {
        // TODO: Implement table() method.
        return $this->getTable('database')
            ->header([
                HeaderItem::label('表名')->prop('name'),
                HeaderItem::label('引擎')->prop('engine'),
                HeaderItem::label('字符集')->prop('collation'),
                HeaderItem::label('数据行数')->prop('rows'),
                HeaderItem::label('数据大小')->prop('data_length'),

                HeaderItem::label('索引大小')->prop('index_length'),
                HeaderItem::label('注释')->prop('comment'),
                HeaderItem::label('创建时间')->prop('create_time'),
                HeaderItem::label('操作')->actions([
                    Actions::view()
                ]),
            ])
            ->withApiRoute('tables')
            ->withSearch([
                Search::text('tablename', '请输入表名'),
                Search::select('engine', '请选择引擎', [
                    ['label' => 'MyISAM', 'value' => 'MyISAM'],
                    ['label' => 'InnoDB', 'value' => 'InnoDB']
                ])
            ])
            ->render();
    }
}