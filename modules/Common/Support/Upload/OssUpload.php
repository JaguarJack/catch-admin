<?php

namespace Modules\Common\Support\Upload;

class OssUpload
{
    protected string $dir = 'upload/';

    protected int $expire = 30;

    protected string $accessKeyId;

    protected string $accessKeySecret;

    protected string $endpoint;

    protected int $maxSize;

    protected string $bucket;

    public function __construct()
    {
        $this->accessKeySecret = config('common.upload.oss.access_secret');

        $this->accessKeyId = config('common.upload.oss.access_id');

        $this->dir = date('Y-m-d').'/';

        $this->endpoint = config('common.upload.oss.endpoint');

        $this->maxSize = config('common.upload.max_size');

        $this->bucket = config('common.upload.oss.bucket');
    }

    /**
     * config
     */
    public function config(): array
    {
        return [
            'bucket' => $this->bucket,

            'accessKeyId' => $this->accessKeyId,

            'host' => sprintf('https://%s.%s', $this->bucket, $this->endpoint),

            'policy' => $this->policy(),

            'signature' => $this->signature(),

            'expire' => $this->getExpiration(),

            'dir' => $this->dir,

            'url' => $this->endpoint.$this->dir,
        ];
    }

    protected function policy(): string
    {
        return base64_encode(json_encode([
            'expiration' => $this->getExpiration(),
            'conditions' => [
                ['starts-with', '$key', $this->dir],
                ['content-length-range', 0, $this->maxSize],
            ],
        ]));
    }

    protected function signature(): string
    {
        return base64_encode(
            \hash_hmac('sha1', $this->policy(), $this->accessKeySecret, true)
        );
    }

    protected function getExpiration(): string
    {
        return date('Y-m-d\TH:i:s\Z', time() + $this->expire);
    }
}
