<?php
// +----------------------------------------------------------------------
// | CatchAdmin [Just Like ～ ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2020 http://catchadmin.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://github.com/yanwenwu/catch-admin/blob/master/LICENSE.txt )
// +----------------------------------------------------------------------
// | Author: JaguarJack [ njphper@gmail.com ]
// +----------------------------------------------------------------------
namespace catchAdmin\system\model\search;

trait AttachmentsSearch
{
    public function searchFileExtAttr($query, $value, $data)
    {
        return $query->where('file_ext', $value);
    }

    public function searchMimeTypesAttr($query, $value, $data)
    {
        return $query->where('mime_type', $value);
    }

    public function searchDriverAttr($query, $value, $data)
    {
        return $query->where('driver', $value);
    }
}