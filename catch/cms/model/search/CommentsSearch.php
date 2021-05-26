<?php
namespace catchAdmin\cms\model\search;

trait CommentsSearch
{
    /**
     * 文章 title 搜索
     *
     * @time 2021年05月26日
     * @param $query
     * @param $value
     * @return mixed
     */
    public function searchTitleAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('title', 'cms_articles'), $value);
    }

    /**
     * 评论人昵称
     *
     * @time 2021年05月26日
     * @param $query
     * @param $value
     * @return mixed
     */
    public function searchUsernameAttr($query, $value)
    {
        return $query->whereLike($this->aliasField('username', 'cms_users'), $value);
    }

    /**
     * 状态搜索
     *
     * @time 2021年05月26日
     * @param $query
     * @param $value
     * @return mixed
     */
    public function searchStatusAttr($query, $value)
    {
        return $query->where($this->aliasField('status'), $value);
    }
}