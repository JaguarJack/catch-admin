<?php
namespace catchAdmin\cms\model\events;

use catchAdmin\cms\model\Tags;

trait TagsEvent
{
    public static function onAfterDelete(Tags $tag)
    {
        $tag->articles()->detach($tag->articles()->select()->column('id'));
    }
}