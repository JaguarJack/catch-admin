<?php
namespace catchAdmin\system\model;

use catcher\base\CatchModel;

class Attachments extends CatchModel
{
    protected $name = 'attachments';
    
    protected $field = [
            'id', // 
			'path', // 附件存储路径
            'url', // 资源地址
			'mime_type', // 资源mimeType
			'file_ext', // 资源后缀
			'file_size', // 资源大小
			'filename', // 资源名称
			'driver', // local,oss,qcloud,qiniu
			'created_at', // 创建时间
			'updated_at', // 更新时间
			'deleted_at', // 删除时间
    ];

    public function getList()
    {
        return $this->order('id', 'desc')
                    ->catchSearch()
                    ->paginate();
    }

    public function searchFileExtAttr($query, $value, $data)
    {
        return $query->where('file_ext', $value);
    }

    public function searchMimeTypesAttr($query, $value, $data)
    {
        return $query->where('mime_type', $value);
    }

    public function searchDriver($query, $value, $data)
    {
        return $query->where('driver', $value);
    }
}
