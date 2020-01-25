<?php
namespace catcher;

use catchAdmin\system\model\Attachments;
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
   * @return array
   * @author JaguarJack
   * @email njphper@gmail.com
   * @time 2020/1/25
   */
    public function upload(UploadedFile $file): array
    {
        $path = Filesystem::disk($this->getDriver())->putFile($this->getPath(), $file);

        if ($path) {
            Attachments::create(array_merge(['path' => $path], $this->data($file)));
        }

        return ['path' => Utils::getCloudDomain($this->getDriver()) . $path];
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
            'file_ext' => $file->getExtension(),
            'filename' => $file->getOriginalName(),
            'driver'  => $this->getDriver(),
        ];
    }
}
