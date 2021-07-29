<?php
// +----------------------------------------------------------------------
// | UCToo [ Universal Convergence Technology ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2021 https://www.uctoo.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: UCToo <contact@uctoo.com>
// +----------------------------------------------------------------------

namespace catchAdmin\apimanager\tables;

use catchAdmin\apimanager\tables\forms\Factory;
use catcher\CatchTable;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;
use catcher\library\table\Table;

class ApiCategory extends CatchTable
{
    /**
     * table
     *
     * @time 2021年03月29日
     * @return array
     */
    protected function table(): array
    {
        // TODO: Implement table() method.
        return $this->getTable('api_category')->header([
            HeaderItem::label('分类标题')->prop('category_title'),
            HeaderItem::label('分类唯一标识')->prop('category_name'),
            HeaderItem::label('排序')->prop('sort')->withEditNumberComponent(),
            HeaderItem::label('状态')->prop('status')->withSwitchComponent(),
            HeaderItem::label('创建时间')->prop('created_at'),
            HeaderItem::label('操作')->width(260)->actions([
                Actions::update(),
                Actions::delete(),
            ])
        ])->withApiRoute('apicategory')->withActions([
            Actions::create()
        ])->withSearch([
            Search::label('分类标题')->text('category_title', '请输入分类标题'),
            Search::label('状态')->status()
        ])->withDialogWidth('35%')
            ->toTreeTable()->render();
    }

    /**
     * form 方式
     *
     * @time 2021年03月29日
     * @return array
     */
    protected function form(): array
    {
        return Factory::create('ApiCategory');
    }
}