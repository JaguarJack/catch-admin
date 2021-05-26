<?php
namespace catchAdmin\cms\model\search;

trait ArticlesSearch
{
    public function searchCategoryAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('name', 'cms_category'), $value);
    }

    public function searchTitleAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('title'), $value);
    }
}