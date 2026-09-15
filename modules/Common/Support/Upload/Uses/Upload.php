<?php

namespace Modules\Common\Support\Upload\Uses;

use Catch\Exceptions\FailedException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

abstract class Upload
{
    /**
     * 默认允许上传的扩展名，后台「上传设置」未配置时生效
     */
    protected const DEFAULT_EXTENSIONS = [
        'image' => ['jpeg', 'jpg', 'gif', 'png', 'svg', 'ico', 'doc', 'psd', 'bmp', 'tiff', 'webp', 'tif', 'pjpeg'],
        'file' => ['docx', 'pdf', 'txt', 'html', 'zip', 'tar', 'doc', 'css', 'csv', 'ppt', 'xlsx', 'xls', 'xml'],
    ];

    /**
     * 脚本类扩展名，无论后台白名单如何配置都禁止上传
     */
    protected const DANGEROUS_EXTENSIONS = ['php', 'phtml', 'phar', 'php3', 'php4', 'php5', 'php7', 'php8', 'pht', 'phps'];

    /**
     * uploadFile object
     */
    protected mixed $file;

    protected array $params;

    /**
     * 上传类型 image|all
     */
    protected string $type = 'all';

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
        $this->checkExtension($this->getUploadedFileExt());
    }

    /**
     * 校验扩展名白名单
     */
    protected function checkExtension(mixed $extension): void
    {
        $extension = $this->normalizeExtension($extension);

        if (! $this->isAllowedExtension($extension)) {
            throw new FailedException(sprintf('不支持该上传文件类型(%s)类型', $extension ?: '未知'));
        }
    }

    /**
     * 是否允许上传
     */
    protected function isAllowedExtension(string $extension): bool
    {
        if ($extension === '' || in_array($extension, self::DANGEROUS_EXTENSIONS, true)) {
            return false;
        }

        return in_array($extension, $this->allowedExtensions(), true);
    }

    /**
     * 当前上传类型允许的扩展名
     *
     * image 仅允许图片；all（通用文件、分片上传）允许图片与文件
     *
     * @return string[]
     */
    protected function allowedExtensions(): array
    {
        $types = $this->type === 'image' ? ['image'] : ['image', 'file'];

        $extensions = [];

        foreach ($types as $type) {
            $extensions = array_merge($extensions, $this->configuredExtensions($type));
        }

        return array_values(array_unique($extensions));
    }

    /**
     * 后台「上传设置」配置的扩展名，未配置时使用默认值
     *
     * @return string[]
     */
    protected function configuredExtensions(string $type): array
    {
        $configured = config(sprintf('upload.%s_exts', $type));

        if (is_string($configured)) {
            $configured = json_decode($configured, true) ?? explode(',', $configured);
        }

        $extensions = [];

        foreach ((array) $configured as $extension) {
            $extension = $this->normalizeExtension($extension);

            if ($extension !== '') {
                $extensions[] = $extension;
            }
        }

        return $extensions ?: (self::DEFAULT_EXTENSIONS[$type] ?? []);
    }

    /**
     * 扩展名归一化
     */
    protected function normalizeExtension(mixed $extension): string
    {
        if (is_array($extension)) {
            $extension = reset($extension);
        }

        return strtolower(ltrim(trim((string) $extension), '.'));
    }

    /**
     * check file size
     */
    protected function checkSize(): void
    {
        $limitSize = (int) config('upload.limit_size', 0) * 1024 * 1024;

        if ($limitSize <= 0) {
            $limitSize = 5 * 1024 * 1024;
        }

        if ($this->getUploadedFileSize() > $limitSize) {
            throw new FailedException('上传最大支持' . ($limitSize / (1024 * 1024)) . 'MB');
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

        $extension = $this->normalizeExtension($this->getUploadedFileExt());

        return in_array($extension, $this->configuredExtensions('image'), true) ? 'image' : 'file';
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
     * 设置上传类型
     *
     * image 仅允许图片，其他值按通用上传处理
     *
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type === 'image' ? 'image' : 'all';

        return $this;
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
