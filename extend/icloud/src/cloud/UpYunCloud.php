<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 11:34
 */
namespace thinking\icloud\cloud;

use thinking\icloud\AbstractCloud;
use thinking\icloud\exception\NotFoundException;
use GuzzleHttp\Psr7\Stream;

class UpYunCloud extends AbstractCloud
{
    const BLOCK_SIZE   = 1024 * 1024;

    const SUCCESS_CODE = 204;

    protected $bucket;
    protected $fileDir;
    protected $stream;
    protected $options;
    protected $filename;
    //本次上传任务的标识，是初始化断点续传任务时响应信息中的
    protected $multiuuid;
    //指定此次分片的唯一 ID，应严格等于上一次请求返回的
    protected $nextpartid;

    /**
     * 创建文件夹
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $directory
     * @throws NotFoundException
     * @return mixed
     */
    public function create(string $bucket, string $directory)
    {
        $uri = sprintf($this->host( 'v0') . '/%s/',  $bucket . $directory );

        return $this->post($uri);
    }

    /**
     * 删除空间
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $directory
     * @throws NotFoundException
     * @return mixed
     */
    public function drop(string $bucket, string $directory)
    {
        $uri = sprintf($this->host( 'v0') . '/%s/', $bucket . $directory );

        return $this->delete($uri);
    }

    /**
     * 获取文件列表
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $directory
     * @param array $options ['x-list-iter' => '分页开始位置', 'x-list-limit' => '获取文件数量', 'x-list-order' => '排序' ]
     * @throws NotFoundException
     * @return mixed
     */
    public function list(string $bucket, string $directory, array $options = ['x-list-limit' => 1])
    {
        $uri = sprintf($this->host( 'v0') . '/%s/', $bucket . $directory );

        return $this->get($uri, $options);
    }

    /**
     * 获取服务容量
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @throws NotFoundException
     * @return mixed
     */
    public function usage(string $bucket)
    {
        $uri = sprintf($this->host( 'v0') .  '/%s/?usage', $bucket );

        return $this->get($uri);
    }

    /**
     * 删除文件
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $fileDir
     * @throws NotFoundException
     * @return mixed
     */
    public function deleteFile(string $bucket, string $fileDir)
    {
        $uri = sprintf($this->host( 'v0') . '/%s/%s' , $bucket, $fileDir );

        return $this->delete($uri);
    }

    /**
     * 下载文件
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $fileDir
     * @throws NotFoundException
     * @return mixed
     */
    public function downloadFile(string $bucket, string $fileDir)
    {
        $uri = sprintf($this->host( 'v0') . '/%s/%s' , $bucket, $fileDir );

        return $this->get($uri, ['stream' => true]);
    }

    /**
     * 上传文件
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $fileDir
     * @param $locationFile
     * @param array $options => 参考http://docs.upyun.com/api/rest_api/#_2
     * @throws NotFoundException
     * @return mixed
     */
    public function uploadFile(string $bucket, string $fileDir, $locationFile, array $options = [])
    {
        if (!is_resource($locationFile)) {
            throw new \Exception('$localfile Must Be Resource Type', 500);
        }
        $stream   = new Stream($locationFile);

        $this->bucket  = $bucket;
        $this->fileDir = $fileDir;
        $this->stream  = $stream;
        $this->options = $options;
        $this->filename = basename($stream->getMetadata('uri'));
        #上传文件大于限制文件大小， 则断点续传
        if ( $stream->getSize() > self::BLOCK_SIZE ) {
            return $this->uploadComplete();
        }

        $uri = sprintf($this->host('v0') . '/%s/%s', $this->bucket, $this->fileDir . $this->filename);

        if (!empty($this->options)) $options['headers'] = $this->options;
        $options['headers'] = ['Content-Length' => $stream->getSize()];
        $options['body']    = $this->stream;

        return $this->put($uri, $this->options);
    }

    /**
     * 初始化断电续传
     *
     * @time at 2019年01月26日
     * @throws NotFoundException
     * @return void
     */
    protected function initUpload()
    {
        $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($this->stream->getMetadata('uri'));

        $headers = [
            'x-upyun-multi-stage'  => 'initiate',
            'x-upyun-multi-length' => $this->stream->getSize(),
            'x-upyun-multi-type'   => $mimeType ? : 'application/octet-stream',
        ];
        $this->options['headers']  = $headers;

        $uri = sprintf($this->host( 'v0') . '/%s/%s', $this->bucket, $this->fileDir . $this->filename);
        $response = $this->put($uri, $this->options);

        if ( !($response->getStatusCode() == self::SUCCESS_CODE) ) {
            throw new \Exception('Failed To Respond');
        }

        $headers = $response->getHeaders();

        if (!isset($headers['x-upyun-multi-uuid'])) {
            throw NotFoundException::NotFoundKey('Response Headers Not Found Key "x-upyun-multi-uuid"');
        }

        if (!isset($headers['x-upyun-next-part-id'])) {
            throw NotFoundException::NotFoundKey('Response Headers Not Found Key "x-upyun-next-part-id"');
        }

        $this->multiuuid  = $headers['x-upyun-multi-uuid'];
        $this->nextpartid = $headers['x-upyun-next-part-id'];
    }

    /**
     * 上传分块
     *
     * @time at 2019年01月26日
     * @throws NotFoundException
     * @return void
     */
    protected function uploading()
    {
        $uploadSize = 0;

        $filesize   = $this->stream->getSize();
        while ($uploadSize < $filesize) {
            //剩余文件大小
            $remainsize = $filesize - $uploadSize;
            //需要读取的文件大小
            $needReadSize = $remainsize > self::BLOCK_SIZE ? self::BLOCK_SIZE : $remainsize;
            $content = $this->stream->read($needReadSize);

            $headrs = [
                'x-upyun-multi-stage' => 'upload',
                'x-upyun-multi-uuid'  => $this->multiuuid,
                'x-upyun-part-id'     => $this->nextpartid,
            ];

            $this->options['body']    = $content;
            $this->options['headers'] = $headrs;
            $uri = sprintf($this->host( 'v0') . '/%s/%s', $this->bucket, $this->fileDir . $this->filename);
            $response = $this->put($uri, $this->options);
            if ( !($response->getStatusCode() == self::SUCCESS_CODE) ) {
                throw new \Exception('Failed To Respond');
            }
            $headers = $response->getHeaders();
            if (!isset($headers['x-upyun-multi-uuid'])) {
                throw NotFoundException::NotFoundKey('Response Headers Not Found Key "x-upyun-multi-uuid"');
            }
            if (!isset($headers['x-upyun-next-part-id'])) {
                throw NotFoundException::NotFoundKey('Response Headers Not Found Key "x-upyun-next-part-id"');
            }
            $this->multiuuid  = $headers['x-upyun-multi-uuid'];
            $this->nextpartid = $headers['x-upyun-next-part-id'];
            $uploadSize += $needReadSize;
        }
    }

    /**
     * 完成上传
     *
     * @time at 2019年01月26日
     * @throws NotFoundException
     * @return mixed
     */
    protected function uploadComplete()
    {
        //初始化
        $this->initUpload();
        //上传
        $this->uploading();
        //合并完成上传
        $headers = [
            'x-upyun-multi-stage' => 'complete',
            'x-upyun-multi-uuid'  => $this->multiuuid,
        ];
        $this->options['headers'] = $headers;

        $uri = sprintf($this->host( 'v0') .'/%s/%s', $this->bucket, $this->fileDir . $this->filename);
        return $this->put($uri, $this->options);
    }
}