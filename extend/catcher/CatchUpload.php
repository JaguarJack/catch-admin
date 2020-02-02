<?php
namespace catcher;

use catchAdmin\system\model\Attachments;
use catcher\exceptions\FailedException;
use catcher\exceptions\ValidateFailedException;
use think\exception\ValidateException;
use think\facade\Filesystem;
use think\file\UploadedFile;

class CatchUpload
{
    /**
     * 阿里云
     */
    public const OSS = 'oss';

    /**
     * 腾讯云
     */
    public const QCLOUD = 'qcloud';

    /**
     * 七牛
     */
    public const QIQNIU = 'qiniu';

    /**
     * 本地
     *
     * @var string
     */
    protected $driver = 'local';

    /**
     * 本地
     */
    public const LOCAL = 'local';

    /**
     * path
     *
     * @var string
     */
    protected $path = '';

  /**
   * upload files
   *
   * @param UploadedFile $file
   * @return string
   * @author JaguarJack
   * @email njphper@gmail.com
   * @time 2020/1/25
   */
    public function upload(UploadedFile $file): string
    {
        $path = Filesystem::disk($this->getDriver())->putFile($this->getPath(), $file);

        if ($path) {
            $url = Utils::getCloudDomain($this->getDriver()) . $path;

            Attachments::create(array_merge([
                'path' => $path,
                'url'  => $url,
            ], $this->data($file)));

            return $url;
        }

        throw new FailedException('Upload Failed, Try Again!');
    }

    /**
     * 多文件上传
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/2/1
     * @param $attachments
     * @return array|string
     */
    public function multiUpload($attachments)
    {
        if (!is_array($attachments)) {
            return $this->upload($attachments);
        }

        $paths = [];
        foreach ($attachments as $attachment) {
            $paths[] = $this->upload($attachment);
        }

        return $paths;
    }

    /**
     * get upload driver
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @return string
     */
    protected function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * set driver
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @param $driver
     * @throws \Exception
     * @return $this
     */
    public function setDriver($driver): self
    {
        if (!in_array($driver, [self::OSS, self::QCLOUD, self::QIQNIU, self::LOCAL])) {
            throw new \Exception(sprintf('Upload Driver [%s] Not Supported', $driver));
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @return string
     */
    protected function getPath()
    {
        return $this->path;
    }

    /**
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/1/25
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

  /**
   *
   * @time 2020年01月25日
   * @param UploadedFile $file
   * @return array
   */
    protected function data(UploadedFile $file)
    {
        return [
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMime(),
            'file_ext' => $file->getOriginalExtension(),
            'filename' => $file->getOriginalName(),
            'driver'  => $this->getDriver(),
        ];
    }

    /**
     * 验证图片
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/2/1
     * @param array $images
     * @return $this
     */
    public function checkImages(array $images)
    {
        try {
            validate(['image' => config('catch.upload.image')])->check($images);
        } catch (ValidateException $e) {
            throw new ValidateFailedException($e->getMessage());
        }

        return $this;
    }

    /**
     * 验证文件
     *
     * @author JaguarJack
     * @email njphper@gmail.com
     * @time 2020/2/1
     * @param array $files
     * @return $this
     */
    public function checkFiles(array $files)
    {
        try {
            validate(['file' => config('catch.upload.file')])->check($files);
        } catch (ValidateException $e) {
            throw new ValidateFailedException($e->getMessage());
        }

        return $this;
    }
}
