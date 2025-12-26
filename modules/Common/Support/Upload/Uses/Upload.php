<?php

namespace Modules\Common\Support\Upload\Uses;

use Catch\Exceptions\FailedException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

abstract class Upload
{
    /**
     * uploadFile object
     */
    protected mixed $file;

    protected array $params;

    abstract public function upload();

    /**
     * @return mixed|true
     */
    protected function dealBeforeUpload(): mixed
    {
        $this->checkExt();

        $this->checkSize();

        // 如果是上传图片资源的的话保存
        // 如果是由其他方式上传的图片路径就直接返回
        if (! $this->file instanceof UploadedFile) {
            return $this->file;
        }

        // if ($this instanceof OssUploadService) {
        //   return $this->file->getPathname();
        // }

        return true;
    }

    public function getUploadPath(): array
    {
        $method = $this->getUploadMethod();

        return $this->info($this->{$method}());
    }

    /**
     * 生成文件名称
     *
     * @time 2019年07月26日
     */
    protected function generateName(string $ext): string
    {
        $filename = $this->params['filename'] ?? '';

        $randomString = date('Y') . Str::random(10) . time();

        if ($filename) {
            $randomString = $filename . '_' . $randomString;
        }

        return md5($randomString) . '.' . $ext;
    }

    /**
     * upload method
     */
    protected function getUploadMethod(): string
    {
        $class = get_called_class();

        $class = explode('\\', $class);

        $className = array_pop($class);

        $method = lcfirst($className);

        if (! method_exists($this, $method)) {
            throw new FailedException(sprintf('Method %s in Class %s Not Found~', $method, $className));
        }

        return $method;
    }

    /**
     * get uploaded file info
     */
    protected function info($path): array
    {
        return [
            'path' => $path,
            'ext' => $this->getUploadedFileExt(),
            'type' => $this->getUploadedFileMimeType(),
            'size' => $this->getUploadedFileSize(),
            'original_name' => $this->getOriginName(),
        ];
    }

    /**
     * check extension
     */
    protected function checkExt(): void
    {
        $extensions = config(sprintf('common.upload.%s.ext', $this->getUploadedFileMimeType()));

        $fileExt = $this->getUploadedFileExt();

        if (! in_array($fileExt, $extensions)) {
            throw new FailedException(sprintf('不支持该上传文件类型(%s)类型', $fileExt));
        }
    }

    /**
     * check file size
     */
    protected function checkSize(): void
    {
        $limitSize = config('common.upload.max_size', 5 * 1024 * 1024);

        $size = $limitSize / (1024 * 1024);

        if ($this->getUploadedFileSize() > $limitSize) {
            throw new FailedException('上传最大支持' . $size . 'MB');
        }
    }

    /**
     * get uploaded file mime type
     */
    protected function getUploadedFileMimeType(): string
    {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientMimeType();
        }

        return in_array($this->getUploadedFileExt(), config('common.upload.image.ext')) ? 'image' : 'file';
    }

    /**
     * get uploaded file extension
     */
    protected function getUploadedFileExt(): array|string
    {
        if ($this->file instanceof UploadedFile) {
            return strtolower($this->file->getClientOriginalExtension());
        }

        // 直传文件
        return pathinfo($this->file, PATHINFO_EXTENSION);
    }

    /**
     * get uploaded file size
     *
     * @return false|int
     */
    protected function getUploadedFileSize(): bool|int
    {
        if ($this->file instanceof UploadedFile) {
            return $this->file->getSize();
        }

        return 0;
    }

    /**
     * get origin name
     */
    public function getOriginName(): ?string
    {
        // 上传图片获取
        if ($this->file instanceof UploadedFile) {
            return $this->file->getClientOriginalName();
        }

        return '';
    }

    /**
     * 参数设置
     *
     * @time 2019年07月25日
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    /**
     * set uploaded file
     *
     * @return $this
     */
    public function setUploadedFile(mixed $file): static
    {
        $this->file = $file;

        return $this;
    }
}
