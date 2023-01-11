<?php

namespace Modules\Common\Support\Upload;

use Catch\Exceptions\FailedException;
use Modules\Common\Support\Upload\Uses\LocalUpload;
use Illuminate\Http\UploadedFile;

class Uploader
{
    protected string $driver = 'local';

    /**
     * path
     *
     * @var string
     */
    protected string $path = '';

    /**
     * upload
     *
     * @param UploadedFile $file
     * @return array
     */
    public function upload(UploadedFile $file): array
    {
        try {
            return $this->getDriver()->setUploadedFile($file)->upload();
        } catch (\Exception $exception) {
            throw new FailedException($exception->getMessage());
        }
    }


    /**
     *  get driver
     *
     */
    public function getDriver()
    {
        $drivers = $this->getDrivers();

        $driver = $drivers[$this->driver] ?? null;

        if (! $driver) {
            throw new FailedException('Upload Driver Not Found');
        }

        return app($driver);
    }


    /**
     * set driver
     *
     * @param string $driver
     * @return $this
     */
    public function setDriver(string $driver): static
    {
        $this->driver =  $driver;

        return $this;
    }

    /**
     * get drivers
     *
     * @return string[]
     */
    public function getDrivers(): array
    {
        return [
          'local' => LocalUpload::class
        ];
    }
}
