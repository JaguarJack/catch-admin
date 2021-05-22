<?php
namespace catchAdmin\cms\tables\forms;

use catchAdmin\cms\model\BaseModel;

class SiteLink extends BaseForm
{
    protected $table = 'cms_site_links';

    public function fields(): array
    {
        // TODO: Implement fields() method.
        return [
            self::input('title', '网站标题')->required(),

            self::input('link_to', '跳转地址')->required()->appendValidates([
                self::validateUrl()
            ]),

            self::image('网站图标', 'icon'),

            self::radio('is_show', '展示', BaseModel::ENABLE)->options(
                self::options()->add('是', BaseModel::ENABLE)
                    ->add('否', BaseModel::DISABLE)->render()
            ),

            self::number('weight', '权重')->min(1)->max(10000)
        ];
    }
}