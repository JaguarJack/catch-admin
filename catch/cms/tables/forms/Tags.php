<?php
namespace catchAdmin\cms\tables\forms;

class Tags extends BaseForm
{
    protected $table = 'cms_tags';

    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            self::input('name', '名称')->required(),

            self::input('title', 'seo 标题'),

            self::input('keywords', 'seo 关键词'),

            self::input('description', 'seo 描述'),
        ];
    }
}