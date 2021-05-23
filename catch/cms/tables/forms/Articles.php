<?php
namespace catchAdmin\cms\tables\forms;

use catchAdmin\cms\model\Tags;
use catchAdmin\cms\model\Articles as Article;
use catchAdmin\cms\model\Category;

class Articles extends BaseForm
{
    protected $table = 'cms_articles';

    public function fields(): array
    {
        $categories =  Category::field(['id', 'name', 'parent_id'])->select()->toTree();

        // TODO: Implement fields() method.
        return [
            self::input('title', '标题')->required()->maxlength(100)->col(12),

            self::cascader('category_id', '选择分类', [])
                ->options($categories)
                ->clearable(true)
                ->filterable(true)
                ->showAllLevels(false)
                ->props([
                'props' => [
                    'value' => 'id',
                    'label' => 'name',
                    'checkStrictly' => true
                ],
                ])->style(['width' => '100%'])
                ->required()->col(8),


            self::input('keywords', '关键字')->col(12),

            self::image('封面', 'cover')->col(12),

            self::textarea('description', '摘要')->col(12),

            self::images('组图', 'images')->col(12),

            self::selectMultiple('tags', '标签')
                ->options(Tags::field(['name as value', 'name as label'])->select()->toArray())
                ->clearable(true)
                ->allowCreate(true)
                ->filterable(true)
                ->style(['width' => '100%'])
                ->col(12),

            self::input('url', '自定义URL')->col(8),

            self::editor('content', '内容')->required(),

            self::radio('is_top', '置顶', Article::UN_TOP)->options(
                self::options()->add('是', Article::TOP)
                    ->add('否', Article::UN_TOP)->render()
            ),

            self::radio('is_recommend', '推荐', Article::UN_RECOMMEND)->options(
                self::options()->add('是', Article::RECOMMEND)
                    ->add('否', Article::UN_RECOMMEND)->render()
            ),

            self::radio('status', '展示', Article::ENABLE)->options(
                self::options()->add('是', Article::ENABLE)
                    ->add('否', Article::DISABLE)->render()
            ),

            self::radio('is_can_comment', '允许评论', Article::UN_CAN_COMMENT)->options(
                self::options()->add('是', Article::CAN_COMMENT)
                    ->add('否', Article::UN_CAN_COMMENT)->render()
            ),
        ];
    }
}