<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/26
 * Time: 11:33
 */
namespace thinking\icloud\cloud;

use thinking\icloud\AbstractCloud;
use finfo;
use thinking\icloud\exception\NotFoundException;
use GuzzleHttp\Psr7\Stream;

class QiNiuCloud extends AbstractCloud
{
    const BLOCK_SIZE = 4 * 1024 * 1024;

    /**
     * 获取 bucket 列表
     *
     * @time at 2019年01月26日
     * @throws NotFoundException
     * @return mixed
     */
    public function buckets()
    {
        $uri = $this->host() . '/buckets' ;

        return $this->get($uri);
    }

    /**
     * 创建 bucket
     *
     * @time at 2019年01月26日
     * @param string $bucket (bucket名称)
     * @param string $region 地区)[z0华东 z1华北 z2华南 na0北美 as0新加坡 ]
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function create(string $bucket, string $region)
    {
        $uri = sprintf($this->host() . '/mkbucketv2/%s/region/%s', self::urlSafeBase64Encode($bucket), $region);

        return $this->post($uri);
    }

    /**
     * 删除空间
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function drop(string $bucket)
    {
        $uri = sprintf($this->host() . '/drop/%s', $bucket);

        return $this->post($uri);
    }

    /**
     * 获取空间名称
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function getDomainListOfBucket(string $bucket)
    {
        $uri = sprintf($this->host( 'api') . '/v6/domain/list?tbl=%s', $bucket);

        return $this->get($uri);
    }

    /**
     * 设置空间权限
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param int $private (0 公开 1 私有)
     * @throws \thinking\icloud\exception\NotFoundException
     * @return bool
     */
    public function setPrivate(string $bucket, int $private = 0)
    {
        if (!in_array($private, [0, 1])) return false;

        $uri = sprintf('%s?%s', $this->host( 'uc')  . '/private', http_build_query(['bucket' => $bucket, 'private' => $private]));

        return $this->post($uri);
    }

    /**
     *
     * 资源统计
     * @space  获取标准存储的存储量统计
     * @count  获取标准存储的文件数量统计
     * @space_line 获取低频存储的存储量统计
     * @count_line 获取低频存储的文件数量统计
     * @blob_transfer 获取跨区域同步流量统计
     * @rs_chtype 获取存储类型请求次数统计
     * @blob_io 获取外网流出流量统计和 GET 请求次数统计
     * @rs_put 获取 PUT 请求次数统计
     * @time at 2019年01月26日
     * @param string $begin
     * @param string $end
     * @param string $type
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function statistics(string $begin, string $end, $type = 'space')
    {
        $urls = [
            'space'              => '/v6/space?begin=%s&end=%s&g=day',
            'count'              => '/v6/count?begin=%s&end=%s&g=day',
            'space_line'         => '/v6/space_line?begin=%s&end=%s&g=day',
            'count_line'         => '/v6/count_line?begin=%s&end=%s&g=day',
            'blob_transfer'      => '/v6/blob_transfer?begin=%s&end=%s&g=day&select=size',
            'rs_chtype'          => '/v6/rs_chtype?begin=%s&end=%s&g=day&select=hits',
            'blob_io'            => '/v6/blob_io?begin=%s&end=%s&g=day&select=flow&$src=origin',
            'rs_put'             => '/v6/rs_put?begin=%s&end=%s&g=day&select=hits',
        ];

        $uri = sprintf($this->host('api') . $urls[$type], $begin, $end);

        return $this->get($uri);
    }

    /**
     * 列出空间所有资源
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $marker
     * @param int $limit
     * @param string $prefix
     * @param string $delimiter
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function list(string $bucket, string $marker = '', int $limit = 10, string $prefix = '', string $delimiter = '')
    {
        $uri = sprintf($this->host('rsf') .'/list?bucket=%s&marker=%s&limit=%d&prefix=%s&delimiter=%s', $bucket, $marker, $limit, $prefix, $delimiter);

        return $this->get($uri);
    }

    /**
     * 获取资源原信息
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $resourceName
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function stat(string $bucket, string $resourceName)
    {
        //1372-the-dawn-of-hope-tomasz-chistowski.jpg

        $encodedEntryUri = $this->encodedEntry($bucket, $resourceName);

        $uri = sprintf($this->host() . '/stat/%s', $encodedEntryUri);

        return $this->get($uri);
    }

    /**
     * 将资源从一个空间移动到另一个空间， 该操作不支持跨账号操作, 不支持跨区域操作
     *
     * @time at 2019年01月26日
     * @param string $localBucket
     * @param string $destBucket
     * @param string $localResourceName
     * @param string $destResourceName
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function move(string $localBucket, string $destBucket, string $localResourceName, string $destResourceName = '')
    {
        $encodedEntryURISrc  = $this->encodedEntry($localBucket, $localResourceName);
        $encodedEntryURIDest = $this->encodedEntry($destBucket, $destResourceName ? : $localResourceName);

        $uri = sprintf($this->host() .'/move/%s/%s' , $encodedEntryURISrc, $encodedEntryURIDest);

        return $this->post($uri);
    }

    /**
     * 将资源从一个空间复制到另一个空间， 该操作不支持跨账号操作, 不支持跨区域操作
     *
     * @time at 2019年01月26日
     * @param string $localBucket
     * @param string $destBucket
     * @param string $localResourceName
     * @param string $destResourceName
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function copy(string $localBucket, string $destBucket, string $localResourceName, string $destResourceName = '')
    {
        $encodedEntryURISrc  = $this->encodedEntry($localBucket, $localResourceName);
        $encodedEntryURIDest = $this->encodedEntry($destBucket, $destResourceName ? : $localResourceName);

        $uri = sprintf($this->host() . '/copy/%s/%s', $encodedEntryURISrc, $encodedEntryURIDest);

        return $this->post($uri);
    }

    /**
     * 删除指定空间资源
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $resourceName
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function delete(string $bucket, string $resourceName)
    {
        $encodedEntryUri = $this->encodedEntry($bucket, $resourceName);

        $uri = sprintf($this->host() . '/delete/%s', $encodedEntryUri);

        return $this->post($uri);
    }

    /**
     * 主权远程 IMG 到指定空间
     *
     * @time at 2019年01月26日
     * @param string $remoteImgUri
     * @param string $destBucket
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function fetch(string $remoteImgUri, string $destBucket)
    {
        $imgEncodedUri   = self::urlSafeBase64Encode($remoteImgUri);
        $encodedEntryUri = self::urlSafeBase64Encode($destBucket);

        $uri = sprintf($this->host( 'iovip') . '/fetch/%s/to/%s', $imgEncodedUri, $encodedEntryUri);

        return $this->post($uri);
    }

    /**
     * 批量操作
     *
     * @说明
     * 数组格式
     * [
     *    stat   => ['bucket', 'resourceName']
     *    delete => ['bucket', 'resourceName']
     *    move   => ['localbucket', 'destbucket', 'resourceName', 'destResourceName'(可不写)]
     *    copy   => ['localbucket', 'destbucket', 'resourceName', 'destResourceName'(可不写)]
     * ]
     * @time at 2019年01月26日
     * @param array $batchOptions
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function batch(array $batchOptions)
    {
        $requestParams = '';

        foreach ($batchOptions as $option => $param)
        {
            if ($option === 'stat' || $option === 'delete') {
                $requestParams .= sprintf('op=/%s/%s&', $option, $this->encodedEntry($param[0], $param[1]));
            } else if($option === 'move' || $option === 'copy') {
                $encodedEntryURISrc  = $this->encodedEntry($param[0], $param[2]);
                $encodedEntryURIDest = $this->encodedEntry($param[1], count($param) >= 4 ? $param[3] : $param[2]);
                $requestParams .= sprintf('op=/%s/%s/%s&', $option, $encodedEntryURISrc, $encodedEntryURIDest);
            } else {
                continue;
            }
        }

        $uri = sprintf('%s?%s', $this->host() . '/batch', $requestParams);

        return $this->post($uri);
    }

    /**
     * 镜像资源更新
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param string $resourceName
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function prefetch(string $bucket, string $resourceName)
    {
        $encodedEntryUri = $this->encodedEntry($bucket, $resourceName);

        $uri = sprintf($this->host('iovip') .'/prefetch/%s' , $encodedEntryUri);

        return $this->post($uri);
    }

    /**
     * Http 直传文件
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param resource $file
     * @param array $params 直传可选参数 => https://developer.qiniu.com/kodo/api/1312/upload
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    public function uploadFile(string $bucket, $file, array $params = [])
    {
        if (!is_resource($file)) {
            throw new \Exception('$file Must Be Resource Type');
        }

        $uri = $this->host( 'up');
        $stream = new Stream($file);
        //判断如果文件大于4M则使用分块上传
        if ($stream->getSize() > self::BLOCK_SIZE) {
            return $this->uploadFileByBlocks($bucket, $file);
        }

        //$filename = md5(basename($stream->getMetadata('uri')) . time());
        $uploadToken = $this->UploadToken($bucket);

        $options['multipart'] = [
            ['name' => 'key', 'contents' =>  basename($stream->getMetadata('uri'))],
            ['name' => 'file', 'contents' => $stream, 'filename' => basename($stream->getMetadata('uri'))],
            ['name' => 'token', 'contents' => $uploadToken],
            ['name' => 'crc32', 'contents' => self::crc32_data($stream)],
            ['name' => 'Content-Type', 'contents' => 'application/octet-stream'],
        ];

        if (!empty($params)) {
            $options['multipart'] = array_merge($params, $options['multipart']);
        }

        return $this->post($uri, $options);
    }

    /**
     * 创建块
     *
     * @time at 2019年01月26日
     * @param string $bucket
     * @param $file
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    protected function uploadFileByBlocks(string $bucket, $file)
    {
        //需要安装fileinfo扩展
        if (!extension_loaded('fileinfo')) {
            throw NotFoundException::NotFoundExtension('PHPExtension Fileinfo Not Found, Please Install It First');
        }
        $stream = new Stream($file);
        $filezie = $stream->getSize();
        //保存ctx值， 用于后续合并文件
        $ctxArr = [];
        //已上传文件大小
        $uploadSize = 0;
        while ($uploadSize < $filezie) {
            //剩余文件大小
            $remainsize = $filezie - $uploadSize;
            //需要读取的文件大小
            $needReadSize = $remainsize > self::BLOCK_SIZE ? self::BLOCK_SIZE : $remainsize;
            $content = $stream->read($needReadSize);
            //创建块并且上传第一个片
            $options['body'] = $content;
            $headers = [
                'Content-Type'   => 'application/octet-stream',
                'Content-Length' => $needReadSize,
            ];
            $options['headers']  = $headers;
            $uri = sprintf($this->host( 'up') .'/mkblk/%s' , $needReadSize);
            $response = $this->post($uri, $options);
            $data = json_decode($response->getBody()->getContents(), true);

            array_push($ctxArr, $data['ctx']);
            $uploadSize += $needReadSize;
        }

        return $this->mkfile($stream, $bucket, $ctxArr);
    }

    /**
     * 创建文件
     *
     * @time at 2019年01月26日
     * @param Stream $stream
     * @param string $bucket
     * @param array $ctx
     * @throws \thinking\icloud\exception\NotFoundException
     * @return mixed
     */
    protected function mkfile(Stream $stream, string $bucket, array $ctx)
    {
        $file     = $stream->getMetadata('uri');
        $key      = self::urlSafeBase64Encode(sprintf('%s', basename($file)));
        $mimetype = (new finfo(FILEINFO_MIME_TYPE))->file($file);
        $filesize = $stream->getSize();
        $userVar  = md5(time());

        $options['headers'] = ['Authorization' => 'UpToken ' . $this->UploadToken($bucket, basename($file))];
        $options['body'] = implode(',', $ctx);

        $uri = sprintf($this->host( 'up') . '/mkfile/%s/key/%s/mimeType/%s/x:user-var/%s', $filesize, $key, self::urlSafeBase64Encode($mimetype), self::urlSafeBase64Encode($userVar));
        return $this->post($uri, $options);
    }
}