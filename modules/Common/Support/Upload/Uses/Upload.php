<?php

namespace Modules\Common\Support\Upload\Uses;

use Catch\Exceptions\FailedException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

abstract class Upload
{
    /**
     * uploadFile object
     *
     * @var mixed
     */
    protected mixed $file;

    /**
     *
     * @var array
     */
    protected array $params;

    public abstract function upload();

    /**
     *
     * @return mixed|true
     */
    protected function dealBeforeUpload(): mixed
    {
        $this->checkExt();

        $this->checkSize();

        // 如果是上传图片资源的的话保存
        // 如果是由其他方式上传的图片路径就直接返回
        if (!$this->file instanceof UploadedFile) {
            return $this->file;
        }

        // if ($this instanceof OssUploadService) {
         //   return $this->file->getPathname();
        // }

        return true;
    }

    /**
     *
     * @return array
     */
    public function getUploadPath(): array
    {
        $method = $this->getUploadMethod();

        return $this->info($this->{$method}());
    }

    /**
     * 生成文件名称
     *
     * @time 2019年07月26日
     * @param string $ext
     * @return string
     */
    protected function generateImageName(string $ext): string
    {
        $filename = $this->params['filename'] ?? '';

        $randomString = date('Y') . Str::random(10) . time();

        if ($filename) {
            $randomString = $filename . '_' . $randomString;
        }

        return $randomString . '.' . $ext;
    }

    /**
     * upload method
     *
     * @return string
     */
    protected function getUploadMethod(): string
    {
        $class = get_called_class();

        $class = explode('\\', $class);

        $className = array_pop($class);

        $method = lcfirst($className);

        if (!method_exists($this, $method)) {
            throw new FailedException(sprintf('Method %s in Class %s Not Found~', $method, $className));
        }

        return $method;
    }

    /**
     * get uploaded file info
     *
     * @param $path
     * @return array
     */
    protected function info($path): array
    {
        return [
            'path'         => $path,
            'ext'          => $this->getUploadedFileExt(),
            'type'         => $this->getUploadedFileMimeType(),
            'size'         => $this->getUploadedFileSize(),
            'originalName' => $this->getOriginName(),
        ];
    }

    /**
     * check extension
     */
    protected function checkExt(): void
    {
        $extensions = config(sprintf('upload.%s.ext', $this->getUploadedFileMimeType()));

        $fileExt = $this->getUploadedFileExt();

        if (!in_array($fileExt, $extensions)) {
            throw new FailedException(sprintf('不支持该上传文件类型(%s)类型', $fileExt));
        }
    }

    /**
     * check file size
     */
    protected function checkSize(): void
    {
        $size = 10 * 1024 * 1024;

        if ($this->getUploadedFileSize() > $size) {
            throw new FailedException('超过文件最大支持的大小');
        }
    }

    /**
     * get uploaded file mime type
     *
     * @return string
     */
    protected function getUploadedFileMimeType(): string
    {
        if ($this->file instanceof UploadedFile) {

            $imageMimeType = [
                'image/gif', 'image/jpeg', 'image/png', 'application/x-shockwave-flash',
                'image/psd', 'image/bmp', 'image/tiff', 'image/jp2',
                'application/x-shockwave-flash', 'image/iff', 'image/vnd.wap.wbmp', 'image/xbm',
                'image/vnd.microsoft.icon', 'image/x-icon', 'image/*', 'image/jpg',
            ];

            return in_array($this->file->getClientMimeType(), $imageMimeType) ? 'image' : 'file';
        }

        return in_array($this->getUploadedFileExt(), config('upload.image.ext')) ? 'image' : 'file';
    }


    /**
     * get uploaded file extension
     *
     * @return array|string
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
     *
     * @return string|null
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
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }

    /**
     * set uploaded file
     *
     * @param mixed $file
     * @return $this
     */
    public function setUploadedFile(mixed $file): static
    {
        $this->file = $file;

        return $this;
    }
}
