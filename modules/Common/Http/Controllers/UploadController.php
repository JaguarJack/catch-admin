<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Common\Support\Upload\Uploader;
use Modules\System\Support\Upload;
use Modules\Common\Events\UploadedEvent;

/**
 * @group 公共模块
 *
 * @subgroup 上传功能
 *
 * @subgroupDescription CatchAdmin 上传
 */
class UploadController
{
    /**
     * 文件上传
     *
     * @bodyParam category_id int required 分类ID
     * @bodyParam `file` file required 文件
     *
     * @responseField driver string 上传驱动名称
     * @responseField path string 上传路径(相对路径，如：/uploads/attachments/2024-09-17/2024V7zVurk7AU1726539554.png)
     * @responseField originName string 原文件名
     * @responseField size int 文件大小
     * @responseField type string 文件类型
     * @responseField ext string 文件扩展名
     * @responseField category_id int 分类ID
     */
    public function file(Request $request, Uploader $uploader): array
    {
        return $uploader->withCategoryId($request->get('category_id', 0))
            ->upload($request->file('file'));
    }

    /**
     * 图片上传
     *
     * @bodyParam category_id int required 分类ID
     * @bodyParam `image` file required 图片
     *
     * @responseField driver string 上传驱动名称
     * @responseField path string 上传路径(相对路径，如：/uploads/attachments/2024-09-17/2024V7zVurk7AU1726539554.png)
     * @responseField originName string 原文件名
     * @responseField size int 文件大小
     * @responseField type string 文件类型
     * @responseField ext string 文件扩展名
     * @responseField category_id int 分类ID
     */
    public function image(Request $request, Uploader $uploader): array
    {
        return $uploader->withCategoryId($request->get('category_id', 0))
            ->upload($request->file('image'));
    }

    /**
     * 分片上传
     *
     * @bodyParam file_name string required 原始文件名
     * @bodyParam file_hash string required 文件SHA-256哈希值
     * @bodyParam chunk_index int required 当前分片索引(从0开始)
     * @bodyParam chunk_hash string required 当前分片MD5哈希值(格式: "索引-hash值")
     * @bodyParam total_chunks int required 总分片数量
     * @bodyParam chunk_size int required 分片大小(字节)
     * @bodyParam total_size int required 文件总大小(字节)
     * @bodyParam chunk file required 分片文件数据
     * @bodyParam disk string required 存储磁盘
     * @bodyParam path string required 存储路径
     * @bodyParam category_id int 分类ID(可选)
     *
     * @responseField success bool 操作是否成功
     * @responseField message string 响应消息
     * @responseField data object 响应数据
     */
    public function chunk(Request $request, Uploader $uploader): array
    {
        $params = $request->only([
            'file_name', 'file_hash', 'chunk_index', 'chunk_hash',
            'total_chunks', 'chunk_size', 'total_size', 'disk', 'path'
        ]);
        $params['action'] = 'chunk';

        $chunkUpload = $uploader->setDriver('chunk')
            ->getDriver()
            ->setUploadedFile($request->file('chunk'))
            ->setParams($params);

        return $chunkUpload->upload();
    }

    /**
     * 合并分片文件
     *
     * @bodyParam file_name string required 原始文件名
     * @bodyParam file_hash string required 文件SHA-256哈希值
     * @bodyParam total_chunks int required 总分片数量
     * @bodyParam total_size int required 文件总大小(字节)
     * @bodyParam disk string required 存储磁盘
     * @bodyParam path string required 存储路径
     * @bodyParam category_id int 分类ID(可选)
     *
     * @responseField success bool 操作是否成功
     * @responseField message string 响应消息
     * @responseField data object 文件信息
     */
    public function merge(Request $request, Uploader $uploader): array
    {
        $params = $request->only([
            'file_name', 'file_hash', 'total_chunks', 'total_size', 'disk', 'path'
        ]);
        $params['action'] = 'merge';

        $chunkUpload = $uploader->setDriver('chunk')
            ->getDriver()
            ->setParams($params);

        $data = $chunkUpload->upload();

        // 触发上传事件
        if ($data) {
            $data['category_id'] = $request->get('category_id', 0);
            UploadedEvent::dispatch($data);
        }

        return $data;
    }

    /**
     * 检查分片上传进度
     *
     * @queryParam file_hash string required 文件哈希值
     *
     * @responseField success bool 操作是否成功
     * @responseField data object 进度信息
     */
    public function checkProgress(Request $request, Uploader $uploader): array
    {
        $params = $request->only(['file_hash']);
        $params['action'] = 'check';

        $chunkUpload = $uploader->setDriver('chunk')
            ->getDriver()
            ->setParams($params);

        return $chunkUpload->upload();
    }

    /**
     * 第三方上传 token
     *
     * @queryParam driver string required 上传驱动名称
     */
    public function token(Request $request): array
    {
        $upload = new Upload;
        $method = $request->get('driver').'Token';

        return $upload->{$method}($request->get('filename'));
    }
}
