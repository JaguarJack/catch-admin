<?php
namespace catchAdmin\cms\tables;

use catcher\CatchTable;
use catchAdmin\cms\tables\forms\Factory;
use catcher\library\table\Actions;
use catcher\library\table\HeaderItem;

class Banners extends CatchTable
{
    public function table()
    {
        // TODO: Implement table() method.
       return $this->getTable('banners')
                    ->header([
                        HeaderItem::label('编号')->prop('id')->width(50),

                        HeaderItem::label('分类')->prop('category'),

                        HeaderItem::label('标题')->prop('title'),

                        HeaderItem::label('图片')->prop('banner_img')->withPreviewComponent(),

                        HeaderItem::label('外链')->prop('link_to')->withUrlComponent(),

                        HeaderItem::label('创建者')->prop('creator'),

                        HeaderItem::label('创建时间')->prop('created_at'),

                        HeaderItem::label('操作')->actions([
                            Actions::update(),
                            Actions::delete()
                        ])
                    ])
                    ->withActions([
                        Actions::create()
                    ])
                    ->withDialogWidth('40%')
                    ->withBind()
                    ->withApiRoute('cms/banners')
                    ->render();
    }

    protected function form()
    {
        // TODO: Implement form() method.
        return Factory::create('banners');
    }
    
}