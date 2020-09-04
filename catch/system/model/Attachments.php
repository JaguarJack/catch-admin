<?php
namespace catchAdmin\system\model;

use catchAdmin\system\model\search\AttachmentsSearch;
use catcher\base\CatchModel;
use think\file\UploadedFile;
use think\Model;

class Attachments extends CatchModel
{
    use AttachmentsSearch;

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
                    ->catchOrder()
                    ->paginate();
    }

    /**
     *
     *
     * @time 2020年06月01日
     * @param $data ['driver' => '', 'path' => '', 'url' => ],
     * @param UploadedFile $file
     * @return Attachments|Model
     */
    public static function store($data, UploadedFile $file)
    {
        return parent::create([
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMime(),
            'file_ext' => $file->getOriginalExtension(),
            'filename' => $file->getOriginalName(),
            'driver'  => $data['driver'],
            'url' => $data['url'],
            'path' => $data['path']
        ]);
    }
}
