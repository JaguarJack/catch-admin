<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;
use catcher\library\table\Search;

class SiteLink extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('SiteLink')
                   ->header([
                       HeaderItem::label('标题')->prop('title'),
                       HeaderItem::label('地址')->prop('link_to')->width(400)->withUrlComponent(),
                       HeaderItem::label('图标')->prop('icon')->width(120)->withPreviewComponent(),
                       HeaderItem::label('展示')->prop('is_show')->width(120)->withSwitchComponent(),
                       HeaderItem::label('权重')->prop('weight')->width(120)->withEditNumberComponent(),
                       HeaderItem::label('操作')->actions([
                           Actions::update(),
                           Actions::delete()
                       ])
                   ])
                   ->withActions([
                       Actions::create()
                   ])
                   ->withSearch([
                       Search::label('网站标题')->input('title', '请输入网站标题')
                   ])
                   ->withBind()
                   ->withDialogWidth('40%')
                   ->withApiRoute('cms/site/links')
                   ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('SiteLink');
    }
    
}